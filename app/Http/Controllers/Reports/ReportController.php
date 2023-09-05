<?php

namespace App\Http\Controllers\Reports;

use App\Exports\RawMaterialStockExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Supply\SupplyController;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Excel;

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
        'url' =>route('reports.raw_material_stock'),
        'description' => 'This text has a line going through it',
      ],
      [
        'label' => 'Cantidad de Talleres con trabajos',
        'url' =>route('reports.raw_material_stock'),
        'description' => 'This text has a line going through it',
      ],
      [
        'label' => 'Detalle de Talleres con material entregado',
        'url' =>route('reports.raw_material_stock'),
        'description' => 'This text has a line going through it',
      ],
      [
        'label' => 'Detalle de despacho de mercaderas',
        'url' =>route('reports.raw_material_stock'),
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
    return Excel::download(new RawMaterialStockExport($data['reports']), 'invoices.xlsx');
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
}
