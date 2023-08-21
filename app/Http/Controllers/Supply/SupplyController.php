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
      $queryBase = Supply::leftJoin('orderentries', 'orderentries.id', '=', 'supplies.orderentry_id')->where('supplies.code', '=', $supp->code);
      $orders = (clone $queryBase)->whereNull('orderentries.deliverydate')->orderBy('orderentries.date', 'DESC')->get();
      $suppliers = $this->listSuppliers($orders);
      $stock_in = (clone $queryBase)->whereNull('orderentries.deliverydate')->sum('supplies.amount');
      $stock_start = (clone $queryBase)->whereNotNull('orderentries.deliverydate')->sum('supplies.amount');
      $stock_out = 0;
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
}
