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

    return redirect()->route('orders.add', ['oid' => $order->id])
                      ->with('message', 'Pedido a <'. $supplier->name. ' en fecha ' .$order->date .'> registrado correctamente.');
  }

  function index() {
    $data = [];
    $data['orders'] = Orderentry::orderBy('date', 'DESC')->with(['supplies', 'supplier'])->get();
    return Inertia::render('Orderentry/Index', $data);
  }

  function view($id) {
    $data = [];
    $data['order'] = Orderentry::with(['supplies', 'supplier'])->find($id);
    return Inertia::render('Orderentry/View', $data);
  }

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
    $order->save();

    $supplier = Supplier::find($request->supplier_id);

    return redirect()->route('orders')
                    ->with('message', 'Pedido a <'. $supplier->name. ' en fecha ' .$order->date .'> actualizado correctamente.');
  }

  function list($id) {
    $data = [];
    $data['order'] = Orderentry::with(['supplies'])->find($id);
    return Inertia::render('Orderentry/List', $data);
  }

  function delete($id) {}

  function receive($id) {
    $data = [];
    $data['order'] = Orderentry::with(['supplies', 'supplier'])->find($id);
    return Inertia::render('Orderentry/Receive', $data);
  }

  function pickup(Request $request, $id) {
    $request->validate([
      'deliverydate' => 'required|date',
      'deliverydoc' => 'nullable|mimes:png,jpg,jpeg,pdf|max:2048',
      'deliveryref' => 'nullable|string|max:255',
      'deliverynote' => 'nullable|string',
    ]);

    $doc_path = $request->deliverydoc->store('documents/order-entries');

    $order = Orderentry::find($id);
    $order->deliverydate = $request->deliverydate;
    $order->deliverynote = $request->deliverynote;
    $order->deliveryref = $request->deliveryref;
    $order->deliverydoc = $doc_path;
    $order->save();

    $supplier = Supplier::find($order->supplier_id);

    return redirect()->route('orders')
                    ->with('message', 'Recepción a <'. $supplier->name. ' en fecha ' .$order->date .'> registrada correctamente.');
  }

  function filters(Request $request) {
    $request->validate([
      'status' => 'required|integer|between:0,2',
    ]);
    
    $orders = Orderentry::orderBy('date', 'DESC')->with(['supplies', 'supplier']);
    if($request->status == 1) {
      $orders = $orders->whereNotNull('deliverydate');
    }
    else if($request->status == 2) {
      $orders = $orders->whereNull('deliverydate');
    }

    $orders = $orders->get();
    return response()->json($orders);
  }
}
