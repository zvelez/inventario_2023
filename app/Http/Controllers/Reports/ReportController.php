<?php

namespace App\Http\Controllers\Reports;

use App\Exports\MerchandiseClearanceExport;
use App\Exports\RawMaterialStockExport;
use App\Exports\WorksDeadlineExport;
use App\Exports\WorkshopsDeliveredMaterialExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Supply\SupplyController;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Startingorder;
use App\Models\Supply;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Excel;
use Carbon\Carbon;

class ReportController extends Controller {
  
  public function main() {
    $data = [];
    $data['reports'] = [
      [
        'label' => 'Materia prima en stock',
        'url' =>route('reports.raw_material_stock'),
        'description' => 'This text has a line going through it',
      ],
      [
        'label' => 'Cantidad de pedidos de clientes y fechas de despacho',
        'url' =>route('reports.works_and_deadline'),
        'description' => 'This text has a line going through it',
      ],
      [
        'label' => 'Cantidad de Talleres con trabajos',
        'url' =>route('reports.workshops_with_jobs'),
        'description' => 'This text has a line going through it',
      ],
      [
        'label' => 'Detalle de Talleres con material entregado',
        'url' =>route('reports.workshops_delivered_material'),
        'description' => 'This text has a line going through it',
      ],
      [
        'label' => 'Detalle de despacho de mercaderías',
        'url' =>route('reports.merchandise_clearance'),
        'description' => 'This text has a line going through it',
      ],
    ];
    return Inertia::render('Report/Main', $data);
  }
  
  public function raw_material_stock() {
    $data = [];
    $data['filters'] = [
      'order' => ['Por fecha de entrega del último proveedor ascendente', 'Por fecha de entrega del último proveedor descendente', 
                  'Por stock actual ascendente', 'Por stock actual descendente', 'Por stock inicial ascendente', 'Por stock inicial descendente'],
      'select' => ['- Todos -', 'Actualmente en Pedido a proveedor', 'Actualmente asignado a trabajo'],
    ];
    return Inertia::render('Report/RawMaterialStock', $data);
  }
  
  public function raw_material_stock_post(Request $request) {
    $data = $this->raw_material_stock_calc($request);
    return response()->json($data);
  }
  
  public function raw_material_stock_download(Request $request) {
    $data = $this->raw_material_stock_calc($request);
    return Excel::download(new RawMaterialStockExport($data['reports']), 'archivo.xlsx');
  }
  
  public function works_and_deadline() {
    $data = [];
    $data['filters'] = [
      'order' => ['Por fecha a entregar ascendente', 'Por fecha a entregar descendente'],
      'select' => [
        '- Todos -',
        'Iniciado',
        'Producción',
        'Revisión',
        'Entregable',
        'Entregado',
      ],
    ];
    return Inertia::render('Report/WorksAndDeadline', $data);
  }
  
  public function works_and_deadline_post(Request $request) {
    $data = $this->works_and_deadline_calc($request);
    return response()->json($data);
  }
  
  public function works_and_deadline_download(Request $request) {
    $data = $this->works_and_deadline_calc($request);
    return Excel::download(new WorksDeadlineExport($data['reports']), 'archivo.xlsx');
  }
  
  public function workshops_with_jobs() {
    $data = [];
    $data['filters'] = [
      'order' => ['Por fecha a entregar ascendente', 'Por fecha a entregar descendente'],
    ];
    return Inertia::render('Report/WorkshopsWithJobs', $data);
  }
  
  public function workshops_with_jobs_post(Request $request) {
    $data = $this->workshops_with_jobs_calc($request);
    return response()->json($data);
  }
  
  public function workshops_with_jobs_download(Request $request) {
    $data = $this->workshops_with_jobs_calc($request);
    return Excel::download(new WorksDeadlineExport($data['reports']), 'archivo.xlsx');
  }
  
  public function workshops_delivered_material() {
    $data = [];
    $data['filters'] = [
      'order' => ['Por fecha a entregar ascendente', 'Por fecha a entregar descendente'],
      'select' => [
        '- Todos -',
        'Iniciado',
        'Producción',
        'Revisión',
        'Entregable',
        'Entregado',
      ],
    ];
    return Inertia::render('Report/WorkshopsDeliveredMaterial', $data);
  }
  
  public function workshops_delivered_material_post(Request $request) {
    $data = $this->workshops_delivered_material_calc($request);
    return response()->json($data);
  }
  
  public function workshops_delivered_material_download(Request $request) {
    $data = $this->workshops_delivered_material_calc($request);
    return Excel::download(new WorkshopsDeliveredMaterialExport($data['reports']), 'archivo.xlsx');
  }
  
  public function merchandise_clearance() {
    $data = [];
    $data['filters'] = [
      'order' => ['Por fecha de despacho ascendente', 'Por fecha de despacho descendente'],
    ];
    return Inertia::render('Report/MerchandiseClearance', $data);
  }
  
  public function merchandise_clearance_post(Request $request) {
    $data = $this->merchandise_clearance_calc($request);
    return response()->json($data);
  }
  
  public function merchandise_clearance_download(Request $request) {
    $data = $this->merchandise_clearance_calc($request);
    return Excel::download(new MerchandiseClearanceExport($data['reports']), 'archivo.xlsx');
  }

  private function raw_material_stock_calc(Request $request) {
    $supplyCtrl = new SupplyController();
    $data = [];
    $supplies = Supply::with('order')->groupBy('supplies.code');
    if($request->select == 1) {
      $queryBase = $supplies->leftJoin('orderentries', 'orderentries.id', '=', 'supplies.orderentry_id')->whereNull('orderentries.deliverydate');
    }
    else if($request->select == 2) {
      $queryBase = $supplies->select(['supplies.*', 'product_assigneds.supply_id', 'product_assigneds.product_id', 
                                      'product_assigneds.amount', 'product_assigneds.created_at as registered_at', 'works.id as work_id'])
      ->leftJoin('product_assigneds', 'product_assigneds.supply_id', '=', 'supplies.id')
      ->leftJoin('products', 'product_assigneds.product_id', '=', 'products.id')
      ->leftJoin('works', 'works.id', '=', 'products.work_id')
      ->whereIn('status', ['Iniciado','Producción','Revisión', 'Entregable']);
    }
    //dd($supplies->toSql());
    $supplies = $supplies->get();
    $data['reports'] = new Collection();
    foreach($supplies as $supp) {
      $queryBase = $supplyCtrl->getQueryEntries($supp);
      $orders = (clone $queryBase)->whereNull('orderentries.deliverydate')->orderBy('orderentries.date', 'DESC')->get();
      $suppliers = $supplyCtrl->listSuppliers($orders);
      $stock_in = (clone $queryBase)->whereNull('orderentries.deliverydate')->sum('supplies.amount');
      $stock_start = (clone $queryBase)->whereNotNull('orderentries.deliverydate')->sum('supplies.amount');
      $stock_total = (clone $queryBase)->sum('supplies.amount');
      $stock_out = $supplyCtrl->calcOutStock($supp);
      $stock_curr = $stock_start + $stock_in - $stock_out;
      $stock_per = $stock_start>0 ? $stock_curr / $stock_start : 0;
      //dd($supp->toArray());
      $data['reports'][] = [
        'code' => $supp->code,
        'description' => $supp->description,
        'brand' => $supp->brand,
        'unitprice' => $supp->unitprice,
        'stock_total' => round($stock_total, 3),
        'stock_start' => round($stock_start, 3),
        'stock_in' => round($stock_in, 3),
        'stock_out' => round($stock_out, 3),
        'stock_curr' => round($stock_curr, 3),
        'stock_perc' => $stock_per,
        'suppliers' => implode(',', $suppliers),
        'order_date' => $supp->order->deliverydate,
      ];
    }
    if($request->order === 0) {
      $data['reports'] = $data['reports']->sortBy([fn ($a, $b) => $a['order_date'] > $b['order_date']]);
    }
    if($request->order === 1) {
      $data['reports'] = $data['reports']->sortBy([fn ($a, $b) => $a['order_date'] <= $b['order_date']]);
    }
    if($request->order === 2) {
      $data['reports'] = $data['reports']->sortBy([fn ($a, $b) => $a['stock_curr'] > $b['stock_curr']]);
    }
    else if($request->order === 3) {
      $data['reports'] = $data['reports']->sortBy([fn ($a, $b) => $a['stock_curr'] <= $b['stock_curr']]);
    }
    if($request->order === 4) {
      $data['reports'] = $data['reports']->sortBy([fn ($a, $b) => $a['stock_start'] > $b['stock_start']]);
    }
    else if($request->order === 5) {
      $data['reports'] = $data['reports']->sortBy([fn ($a, $b) => $a['stock_start'] <= $b['stock_start']]);
    }
    return $data;
  }

  private function works_and_deadline_calc(Request $request) {
    $direction = $request->order == 1? 'DESC' : 'ASC';
    $data = [];
    $products = Product::leftJoin('works', 'products.work_id', 'works.id')->with(['work', 'work.client', 'manufacturer']);
    $products = $products->orderBy('works.deadline', $direction)->orderBy('products.work_id', 'ASC');
    if($request->select != '- Todos -') {
      $products = $products->where('works.status', '=', $request->select);
    }
    $products = $products->get();
    $data['reports'] = new Collection();
    foreach($products as $prod) {
      $data['reports'][] = [
        'code' => $prod->work_id,
        'client' => $prod->work->client->fullname,
        'deadline' => Carbon::createFromFormat('Y-m-d', $prod->work->deadline)->format('d/m/Y'),
        'status' => $prod->work->status,
        'product_code' => $prod->code,
        'product_name' => $prod->name,
        'product_amount' => $prod->amount,
        'product_price' => $prod->unitprice,
        'workshop' => $prod->manufacturer->fullname,
      ];
    }
    return $data;
  }

  private function workshops_with_jobs_calc(Request $request) {
    $direction = $request->order == 1? 'DESC' : 'ASC';
    $data = [];
    $products = Product::select(['products.*'])
        ->leftJoin('works', 'products.work_id', 'works.id')
        ->where('works.status', '=', 'Producción')
        ->with(['work', 'work.client', 'manufacturer']);
    $products = $products->orderBy('works.deadline', $direction)->orderBy('products.work_id', 'ASC');
    $products = $products->get();
    //dd($products);
    $data['reports'] = new Collection();
    foreach($products as $prod) {
      $supps = Supply::leftJoin('product_assigneds', 'product_assigneds.supply_id', 'supplies.id')
          ->where('product_assigneds.product_id', '=', $prod->id)
          ->get();
      $suppIDs = [];
      foreach($supps as $spp) {
        $suppIDs[] = $spp->code;
      }
      $delivery_supply = Delivery::where('in', '=', 0)->where('work_id', '=', $prod->work_id)
                            ->whereIn('code', $suppIDs)->orderBy('deliverydate', 'ASC')->first();
      $delivery_prod = Delivery::where('in', '=', 1)->where('work_id', '=', $prod->work_id)
                            ->where('code', '=', $prod->code)->orderBy('deliverydate', 'DESC')->sum('amount');
      $data['reports'][] = [
        'code' => $prod->work_id,
        'client' => $prod->work->client->fullname,
        'deadline' => Carbon::createFromFormat('Y-m-d', $prod->work->deadline)->format('d/m/Y'),
        'first_delivery' => empty($delivery_supply) ? 'Aún no se hizo' : $delivery_supply->created_at->format('d/m/Y'),
        'delivery_amount' => $delivery_prod,
        'product_code' => $prod->code,
        'product_name' => $prod->name,
        'product_amount' => $prod->amount,
        'workshop' => $prod->manufacturer->fullname,
      ];
    }
    return $data;
  }

  private function workshops_delivered_material_calc(Request $request) {
    $direction = $request->order == 1? 'DESC' : 'ASC';
    $data = [];
    $products = Product::select(['products.*'])
        ->leftJoin('works', 'products.work_id', 'works.id')
        ->with(['work', 'work.client', 'manufacturer']);
    $products = $products->orderBy('works.deadline', $direction)->orderBy('products.work_id', 'ASC');
    $products = $products->get();
    //dd($products);
    $data['reports'] = new Collection();
    foreach($products as $prod) {
      $supps = Supply::leftJoin('product_assigneds', 'product_assigneds.supply_id', 'supplies.id')
          ->where('product_assigneds.product_id', '=', $prod->id)
          ->get();
      $suppIDs = [];
      foreach($supps as $spp) {
        $suppIDs[] = $spp->code;
      }
      $delivery_prod = Delivery::where('in', '=', 1)->where('work_id', '=', $prod->work_id)
            ->where('code', '=', $prod->code)->orderBy('deliverydate', 'DESC')->sum('amount');
      $data['reports'][] = [
        'workshop' => $prod->manufacturer->fullname,
        'deadline' => Carbon::createFromFormat('Y-m-d', $prod->work->deadline)->format('d/m/Y'),
        'work_id' => $prod->work_id,
        'work_status' => $prod->work->status,
        'product_code' => $prod->code,
        'product_name' => $prod->name,
        'product_amount' => $prod->amount,
        'delivery_amount' => $delivery_prod,
      ];
    }
    return $data;
  }
  private function merchandise_clearance_calc(Request $request) {
    $direction = $request->order == 1? 'DESC' : 'ASC';
    $orders = Startingorder::with(['work', 'work.client', 'user'])->orderBy('dispatchdate', $direction)->get();
    $data = [];
    $data['reports'] = new Collection();
    foreach($orders as $ord) {
      $data['reports'][] = [
        'work_code' => $ord->work->id,
        'dispatchdate' => Carbon::createFromFormat('Y-m-d H:i:s', $ord->dispatchdate)->format('d/m/Y H:i'),
        'client' => $ord->work->client->fullname,
        'supervisor' => $ord->user->fullname,
        'receivedby' => $ord->receivedby,
      ];
    }
    return $data;
  }
}
