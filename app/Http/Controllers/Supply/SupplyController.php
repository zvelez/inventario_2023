<?php

namespace App\Http\Controllers\Supply;

use App\Http\Controllers\Controller;
use App\Models\Orderentry;
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
      'code' => 'required|string|max:255',
      'description' => 'required|string',
      'brand' => 'required|string|max:255',
      'amount' => 'required|numeric',
      'orderentry_id' => 'required',
    ]);

    $supply = Supply::create([
      'code' => $request->code,
      'description' => $request->description,
      'brand' => $request->brand,
      'amount' => $request->amount,
      'unitprice' => 0,
      'deliverynote' => '',
      'orderentry_id' => $request->orderentry_id,
    ]);

    $order = Orderentry::with('supplier')->find($request->orderentry_id);

    return redirect()->route('orders', ['oid' => $supply->id])
                      ->with('message', 'Insumo <'. $supply->code .'> registrado correctamente en el pedido al Proveedor <'. $order->supplier->name . ' en fecha ' . $order->date .'>.');
  }
}
