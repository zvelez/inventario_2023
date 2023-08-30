<?php

namespace App\Http\Controllers\Supply;

use App\Http\Controllers\Controller;
use App\Models\Orderentry;
use App\Models\Supplier;
use App\Models\Supply;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplyController extends Controller {
  
  function create($oid) {
    $data = [];
    $data['supply'] = new Supply();
    $data['order'] = Orderentry::with('supplier')->find($oid);
    return Inertia::render('Supply/Form', $data);
  }

  function store(Request $request) {
    $request->validate([
      'code' => 'nullable|string|max:255',
      'description' => 'required|string',
      'brand' => 'required|string|max:255',
      'amount' => 'required|numeric',
      'orderentry_id' => 'required',
      'op' => 'required',
    ]);

    $supply = Supply::create([
      'code' => NULL,
      'description' => $request->description,
      'brand' => $request->brand,
      'amount' => $request->amount,
      'unitprice' => 0,
      'deliverynote' => NULL,
      'orderentry_id' => $request->orderentry_id,
    ]);

    $order = Orderentry::with('supplier')->find($request->orderentry_id);

    $redirect = $request->op==='end' ? redirect()->route('orders') : back();
    return $redirect->with('message', 'Insumo <'. $supply->code .'> registrado correctamente en el pedido al Proveedor <'. $order->supplier->name . ' en fecha ' . $order->date .'>.');
  }

  function index() {
    $data = [];
    $supplies = Supply::with('order')->groupBy('code')->get();

    $data['supplies'] = [];
    foreach($supplies as $supp) {
      $queryBase = $this->getQueryEntries($supp);
      $orders = (clone $queryBase)->whereNull('orderentries.deliverydate')->orderBy('orderentries.date', 'DESC')->get();
      $suppliers = $this->listSuppliers($orders);
      $stock_in = (clone $queryBase)->whereNull('orderentries.deliverydate')->sum('supplies.amount');
      $stock_start = (clone $queryBase)->whereNotNull('orderentries.deliverydate')->sum('supplies.amount');
      $stock_out = $this->calcOutStock($supp);
      $stock_curr = $stock_start + $stock_in - $stock_out;
      $data['supplies'][] = [
        'code' => $supp->code,
        'description' => $supp->description,
        'brand' => $supp->brand,
        'unitprice' => $supp->unitprice,
        'stock_start' => round($stock_start, 3),
        'stock_in' => round($stock_in, 3),
        'stock_out' => round($stock_out, 3),
        'stock_curr' => round($stock_curr, 3),
        'suppliers' => implode(',', $suppliers),
      ];
    }
    return Inertia::render('Supply/Index', $data);
  }

  function view($id) {
    $data = [];
    $data['supply'] = Supply::with(['order'])->find($id);
    return Inertia::render('Supply/View', $data);
  }

  function update($id) {
    $data = [];
    $data['supply'] = Supply::find($id);
    $data['order'] = Orderentry::with('supplier')->find($id);
    return Inertia::render('Supply/Form', $data);
  }

  function receive($id) {
  }

  function pickup($id, Request $request) {
    $request->validate([
      'amount' => 'required|numeric',
      'unitprice' => 'required|numeric',
    ]);

    $supply = Supply::find($id);
    $supply->amount = $request->amount;
    $supply->unitprice = $request->unitprice;
    $supply->save();

    return response()->json($supply);
  }

  private function listSuppliers($orders) {
    $suppIds = [];
    foreach($orders as $ord) {
      $suppIds[] = $ord->supplier_id;
    }
    $suppliers = Supplier::whereIn('id', $suppIds)->get(); 
    if(sizeof($orders) > 0) {
      //dd($suppliers->toArray(), $suppIds, $orders->toArray());
    }
    $suppText = [];
    foreach($suppliers as $suppl) {
      $suppText[] = $suppl->name;
    }
    return $suppText;
  }

  public function entries() {
    $data = [];
    $data['supplies'] = Supply::with(['order', 'order.supplier'])->get();
    //dd($data['supplies']->toArray());
    return Inertia::render('Supply/Entries', $data);
  }

  public function deliveries() {
    $data = [];
    $data['supplies'] = $this->getQueryDeliveries(null)
        ->with(['products', 'products.manufacturer', 'products.work', 'products.work.client'])
        ->whereNotNull('product_assigneds.product_id')
        ->get();
    //dd($data['supplies']->toArray());
    return Inertia::render('Supply/Deliveries', $data);
  }

  public function search(Request $request) {
    $supplies = Supply::where('code', 'like', '%'.$request->text.'%')->orWhere('brand', 'like', '%'.$request->text.'%')
          ->orWhere('description', 'like', '%'.$request->text.'%')->get();
    $response = [];
    foreach($supplies as $supply) {
      $response[] = ['id' => $supply->id, 'label' => $supply->code, 'data' => $supply];
    }

    return response()->json($response);
  }

  private function getQueryEntries($supp) {
    $qb = Supply::leftJoin('orderentries', 'orderentries.id', '=', 'supplies.orderentry_id');
    if(!empty($supp)) {
      $qb->where('supplies.code', '=', $supp->code);
    }
    return $qb;
  }

  private function getQueryDeliveries($supp) {
    $qb = Supply::select(['supplies.*', 'product_assigneds.supply_id', 'product_assigneds.product_id', 
                          'product_assigneds.amount', 'product_assigneds.created_at as registered_at', 'works.id as work_id'])
    ->leftJoin('product_assigneds', 'product_assigneds.supply_id', '=', 'supplies.id')
    ->leftJoin('products', 'product_assigneds.product_id', '=', 'products.id')
    ->leftJoin('works', 'works.id', '=', 'products.work_id');
    if(!empty($supp)) {
      $qb->where('supplies.code', '=', $supp->code);
    }
    return $qb;
  }

  private function calcOutStock($supp) {
    $queryBase = $this->getQueryDeliveries($supp);
    //dd($queryBase->get()->toArray());
    return $queryBase->sum('product_assigneds.amount');
  }
}
