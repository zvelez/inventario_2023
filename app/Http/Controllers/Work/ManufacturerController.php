<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller {

  public function index() {
    //
  }

  public function create() {
    //
    ['fullname',
    'email',
    'contact',
    'address',
    'description',
    'phone',];
  }

  public function store(Request $request) {
    $request->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'contact' => 'required|string|max:255',
      'address' => 'required|string|max:255',
      'phone' => 'required|string|max:255',
      'description' => 'required|string',
    ]);

    $manufacturer = Manufacturer::create([
      'fullname' => $request->fullname,
      'email' => $request->email,
      'contact' => $request->contact,
      'address' => $request->address,
      'phone' => $request->phone,
      'description' => $request->description,
    ]);

    return back()->with('message', 'Taller <'. $manufacturer->code .'> registrado correctamente.');
  }

  public function show($id) {
    //
  }

  public function edit($id) {
    //
  }

  public function update(Request $request, $id) {
    //
  }

  public function destroy($id) {
    //
  }

  public function search(Request $request) {
    $manufacturer = Manufacturer::where('fullname', 'like', '%'.$request->text.'%')->orWhere('contact', 'like', '%'.$request->text.'%')->get();
    $response = [];
    foreach($manufacturer as $man) {
      $response[] = ['id' => $man->id, 'label' => $man->fullname];
    }

    return response()->json($response);
  }
}
