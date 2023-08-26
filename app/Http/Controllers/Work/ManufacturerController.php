<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManufacturerController extends Controller {

  public function index() {
    $data = [];
    $data['manufacturers'] = Manufacturer::orderBy('created_at', 'DESC')->get();
    return Inertia::render('Manufacturer/Index', $data);
  }

  public function create() {
    $data = [];
    $data['manufacturer'] = new Manufacturer();
    return Inertia::render('Manufacturer/Form', $data);
  }

  public function store(Request $request) {
    $request->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'contact' => 'required|string|max:255',
      'address' => 'required|string|max:255',
      'phone' => 'required|string|max:255',
      'description' => 'nullable|string',
    ]);

    $manufacturer = Manufacturer::create([
      'fullname' => $request->fullname,
      'email' => $request->email,
      'contact' => $request->contact,
      'address' => $request->address,
      'phone' => $request->phone,
      'description' => $request->description,
    ]);

    return redirect()->route('manufacturers')
              ->with('message', 'Taller <'. $manufacturer->code .'> registrado correctamente.');
  }

  public function show($id) {
    //
  }

  public function update($id) {
    $data = [];
    $data['manufacturer'] = Manufacturer::find($id);
    return Inertia::render('Manufacturer/Form', $data);
  }

  public function edit($id, Request $request) {
    $request->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'contact' => 'required|string|max:255',
      'address' => 'required|string|max:255',
      'phone' => 'required|string|max:255',
      'description' => 'nullable|string',
    ]);

    $manufacturer = Manufacturer::find($id);
    $manufacturer->fullname = $request->fullname;
    $manufacturer->email = $request->email;
    $manufacturer->contact = $request->contact;
    $manufacturer->address = $request->address;
    $manufacturer->phone = $request->phone;
    $manufacturer->description = $request->description;
    $manufacturer->save();

    return redirect()->route('manufacturers')
              ->with('message', 'Taller <'. $manufacturer->code .'> registrado correctamente.');
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
