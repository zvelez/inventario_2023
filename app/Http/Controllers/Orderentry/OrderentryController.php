<?php

namespace App\Http\Controllers\Orderentry;

use App\Http\Controllers\Controller;
use App\Models\Orderentry;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderentryController extends Controller {
  
  function create() {
    $data = [];
    $data['order'] = new Orderentry();
    $data['suppliers'] = [];
    $suppliers = Supplier::get();
    foreach($suppliers as $sup) {
      $data['suppliers'][] = ['id' => $sup->id, 'label'=>$sup->name];
    }
    
    return Inertia::render('Orderentry/Form', $data);
  }

  function store(Request $request) {
    $request->validate([
      'date' => 'required|date',
      'solicitantarea' => 'required|string|max:255',
      'solicitantmanager' => 'required|string|max:255',
      'solicitantdate' => 'required|date',
      'deliveryaddress' => 'required|string|max:255',
      'deliveryreceptionist' => 'required|string|max:255',
      'estimateddeliverydate' => 'required|date',
      'supplier_id' => 'required',
    ]);

    $order = Orderentry::create([
      'date' => $request->date,
      'solicitantarea' => $request->solicitantarea,
      'solicitantmanager' => $request->solicitantmanager,
      'solicitantdate' => $request->solicitantdate,
      'deliveryaddress' => $request->deliveryaddress,
      'deliveryreceptionist' => $request->deliveryreceptionist,
      'estimateddeliverydate' => $request->estimateddeliverydate,
      'supplier_id' => $request->supplier_id,
      //'deliverydate' => $request->deliverydate,
      //'deliverynote' => $request->deliverynote,
      //'deliveryref' => $request->deliveryref,
      //'deliverydoc' => $request->deliverydoc,
    ]);

    $supplier = Supplier::find($request->supplier_id);

    return redirect()->route('orders')
                      ->with('message', 'Pedido a <'. $supplier->name. ' en fecha ' .$order->date .'> registrado correctamente.');
  }

  function index() {
    $data = [];
    $data['orders'] = Orderentry::orderBy('created_at', 'DESC')->with(['supplies', 'supplier'])->get();
    return Inertia::render('Orderentry/Index', $data);
  }

  function view() {}

  function update($id) {
    $data = [];
    $data['order'] = Orderentry::find($id);
    $data['suppliers'] = [];
    $suppliers = Supplier::get();
    foreach($suppliers as $sup) {
      $data['suppliers'][] = ['id' => $sup->id, 'label'=>$sup->name];
    }
    return Inertia::render('Orderentry/Form', $data);
  }

  function edit(Request $request, $id) {
    $request->validate([
      'date' => 'required|date',
      'solicitantarea' => 'required|string|max:255',
      'solicitantmanager' => 'required|string|max:255',
      'solicitantdate' => 'required|date',
      'deliveryaddress' => 'required|string|max:255',
      'deliveryreceptionist' => 'required|string|max:255',
      'estimateddeliverydate' => 'required|date',
      'supplier_id' => 'required',
    ]);

    $order = Orderentry::find($id);
    $order->date = $request->date;
    $order->solicitantarea = $request->solicitantarea;
    $order->solicitantmanager = $request->solicitantmanager;
    $order->solicitantdate = $request->solicitantdate;
    $order->deliveryaddress = $request->deliveryaddress;
    $order->deliveryreceptionist = $request->deliveryreceptionist;
    $order->estimateddeliverydate = $request->estimateddeliverydate;
    $order->supplier_id = $request->supplier_id;
    //$order->deliverydate = $request->deliverydate;
    //$order->deliverynote = $request->deliverynote;
    //$order->deliveryref = $request->deliveryref;
    //$order->deliverydoc = $request->deliverydoc;
    $order->save();

    $supplier = Supplier::find($request->supplier_id);

    return redirect()->route('orders')
                    ->with('message', 'Pedido a <'. $supplier->name. ' en fecha ' .$order->date .'> actualizado correctamente.');
  }

  function delete($id) {}
}
