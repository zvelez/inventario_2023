<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAssigned;
use App\Models\Productphoto;
use App\Models\Work;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Image;

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
    $data['product'] = Product::with(['supplies', 'manufacturer'])->find($id);
    $data['work'] = Work::with(['client'])->find($data['product']->work_id);

    return Inertia::render('Product/Form', $data);
  }

  function edit(Request $request, $id) {
    $request->validate([
      'code' => 'required|string|max:255',
      'name' => 'required|string|max:255',
      'amount' => 'required|numeric',
      'unitprice' => 'required|numeric',
    ]);
    //dd($request->supplies);
    $product = Product::find($id);
    $product->code = $request->code;
    $product->name = $request->name;
    $product->amount = $request->amount;
    $product->unitprice = $request->unitprice;
    $product->save();

    foreach($request->supplies as $supp) {
      $ass = ProductAssigned::where('product_id', '=', $id)
                    ->where('supply_id', '=', $supp['id'])->first();
      if(empty($ass)) {
        ProductAssigned::create([
          'product_id' => $product->id,
          'supply_id' => $supp['id'],
          'amount' => $supp['pivot']['amount'],
        ]);
      }
      else {
        $ass->amount = floatval($supp['pivot']['amount']);
        $ass->save();
      }
    }

    return redirect()->route('work-progress')->with('message', 'Producto <'. $product->code .'> actualizado correctamente.');
  }

  function addPhoto($id) {
    $data = [];
    $data['product'] = Product::find($id);
    $data['photo'] = new Productphoto();
    return Inertia::render('Product/FormPhoto', $data);
  }

  function savePhoto(Request $request, $id) {
    $data = $request->all();
    $img_url = "product-".time().".png";
    $path = '/images/products/';
    $directory = public_path().$path;
    if (!file_exists($directory)) {
      mkdir($directory, 0777, true);
    }
    $filepath = str_replace('/', DIRECTORY_SEPARATOR, $directory) . $img_url;
    $img = Image::make(file_get_contents($data['file']))->save($filepath);

    $photo = new Productphoto();
    $photo->photourl = $path.$img_url;
    $photo->description = $data['description'];
    $photo->product_id = $id;
    $photo->save();
    $product = Product::find($id);
    return redirect()->route('works.list', ['id' => $product->work_id])->with('message', 'Fotografía registrada correctamente.');
  }
}
