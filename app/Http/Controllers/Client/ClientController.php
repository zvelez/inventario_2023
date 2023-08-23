<?php

namespace App\Http\Controllers\Client;

use Inertia\Inertia;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class ClientController extends Controller {
  
  function create() {
    $data = [];
    $data['client'] = new Client();
    return Inertia::render('Client/Form', $data);
  }

  function store(Request $request) {
    $request->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'address' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'state' => 'required|string|max:255',
      'phone' => 'required|string|max:8',
    ]);

    $client = Client::create([
      'fullname' => $request->fullname,
      'email' => $request->email,
      'address' => $request->address,
      'city' => $request->city,
      'state' => $request->state,
      'phone' => $request->phone,
      'gender' => 1,
    ]);

    event(new Registered($client));

    return redirect()->route('clients')
                      ->with('message', 'Cliente <'.$client->fullname.'> registrado correctamente.');
  }

  function index() {
    $data = [];
    $data['clients'] = Client::orderBy('created_at', 'DESC')->get();
    return Inertia::render('Client/Index', $data);
  }

  function view() {}

  function update($id) {
    $data = [];
    $data['client'] = Client::find($id);
    return Inertia::render('Client/Form', $data);
  }

  function edit(Request $request, $id) {
    $request->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'address' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'state' => 'required|string|max:255',
      'phone' => 'required|string|max:8',
    ]);

    $client = Client::find($id);
    $client->fullname = $request->fullname;
    $client->email = $request->email;
    $client->address = $request->address;
    $client->city = $request->city;
    $client->state = $request->state;
    $client->phone = $request->phone;
    $client->gender = 1;
    $client->save();

    return redirect()->route('clients')
                    ->with('message', 'Usuario <'.$client->fullname.'> actualizado correctamente.');
  }

  function delete($id) {}

  function search(Request $request) {
    $clients = Client::where('fullname', 'like', '%'.$request->text.'%')->get();
    $response = [];
    foreach($clients as $cl) {
      $response[] = ['id' => $cl->id, 'label' => $cl->fullname];
    }

    return response()->json($response);
  }
}
