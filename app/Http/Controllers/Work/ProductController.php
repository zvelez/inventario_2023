<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAssigned;
use App\Models\Work;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller {
  
  function create($wid) {
    $data = [];
    $data['product'] = new Product();
    $data['work'] = Work::with(['client'])->find($wid);
    return Inertia::render('Product/Form', $data);
  }

  function store(Request $request, $wid) {
    $request->validate([
      'code' => 'required|string|max:255',
      'name' => 'required|string|max:255',
      'amount' => 'required|numeric',
      'unitprice' => 'required|numeric',
      'manufacturer_id' => 'required',
      'op' => 'required',
    ]);

    $product = Product::create([
      'manufacturer_id' => $request->manufacturer_id,
      'work_id' => $wid,
      'code' => $request->code,
      'name' => $request->name,
      'amount' => $request->amount,
      'unitprice' => $request->unitprice,
    ]);

    foreach($request->supplies as $supp) {
      ProductAssigned::create([
        'product_id' => $product->id,
        'supply_id' => $supp['id'],
        'amount' => $supp['amount'],
      ]);
    }

    $work = Work::with(['client'])->find($wid);

    $redirect = $request->op==='end' ? redirect()->route('work-progress') : back();
    return $redirect->with('message', 'Producto <'. $product->code .'> registrado correctamente al Trabajo de <'. $work->client->fullname . ' en fecha ' . $work->deadline .'>.');
  }
  
  function update($id) {
    $data = [];
    $data['product'] = Product::with(['supplies'])->find($id);
    $data['work'] = Work::with(['client'])->find($data['product']->work_id);
    return Inertia::render('Product/Form', $data);
  }

  function edit(Request $request, $wid) {
    $request->validate([
      'code' => 'required|string|max:255',
      'name' => 'required|string|max:255',
      'amount' => 'required|numeric',
      'unitprice' => 'required|numeric',
      'manufacturer_id' => 'required',
      'op' => 'required',
    ]);

    $product = Product::create([
      'manufacturer_id' => $request->manufacturer_id,
      'work_id' => $wid,
      'code' => $request->code,
      'name' => $request->name,
      'amount' => $request->amount,
      'unitprice' => $request->unitprice,
    ]);

    $work = Work::with(['client'])->find($wid);

    $redirect = $request->op==='end' ? redirect()->route('work-progress') : back();
    return $redirect->with('message', 'Producto <'. $product->code .'> registrado correctamente al Trabajo de <'. $work->client->fullname . ' en fecha ' . $work->deadline .'>.');
  }
}
