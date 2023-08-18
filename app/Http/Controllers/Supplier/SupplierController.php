<?php

namespace App\Http\Controllers\Supplier;

use Inertia\Inertia;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;

class SupplierController extends Controller {
  
  function create() {
    $data = [];
    $data['supplier'] = new Supplier();
    return Inertia::render('Supplier/Form', $data);
  }

  function store(Request $request) {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'address' => 'required|string|max:255',
      'contact' => 'required|string|max:255',
      'phone' => 'required|string|max:8',
    ]);

    $supplier = Supplier::create([
      'name' => $request->name,
      'email' => $request->email,
      'address' => $request->address,
      'contact' => $request->contact,
      'phone' => $request->phone,
    ]);

    event(new Registered($supplier));

    return redirect()->route('suppliers')
                      ->with('message', 'Proveedor <'.$supplier->name.'> registrado correctamente.');
  }

  function index() {
    $data = [];
    $data['suppliers'] = Supplier::orderBy('created_at', 'DESC')->get();
    return Inertia::render('Supplier/Index', $data);
  }

  function update($id) {
    $data = [];
    $data['supplier'] = Supplier::find($id);
    return Inertia::render('Supplier/Form', $data);
  }

  function edit(Request $request, $id) {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'address' => 'required|string|max:255',
      'contact' => 'required|string|max:255',
      'phone' => 'required|string|max:8',
    ]);

    $supplier = Supplier::find($id);
    $supplier->name = $request->name;
    $supplier->email = $request->email;
    $supplier->address = $request->address;
    $supplier->contact = $request->contact;
    $supplier->phone = $request->phone;
    $supplier->save();

    return redirect()->route('suppliers')
                    ->with('message', 'Proveedor <'.$supplier->name.'> actualizado correctamente.');
  }

  function delete($id) {}
}
