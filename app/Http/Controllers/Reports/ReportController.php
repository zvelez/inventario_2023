<?php

namespace App\Http\Controllers\Reports;

use App\Exports\RawMaterialStockExport;
use App\Exports\WorksDeadlineExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Supply\SupplyController;
use App\Models\Product;
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
    $data = $this->raw_material_stock_calc($request);
    return response()->json($data);
  }
  
  public function works_and_deadline_download(Request $request) {
    $data = $this->raw_material_stock_calc($request);
    return Excel::download(new WorksDeadlineExport($data['reports']), 'archivo.xlsx');
  }

  private function raw_material_stock_calc(Request $request) {
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
}
