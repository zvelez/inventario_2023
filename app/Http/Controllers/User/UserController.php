<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserController extends Controller {
  
  function create() {
    $data = [];
    $data['user'] = new User();
    return Inertia::render('User/Form', $data);
  }

  function store(Request $request) {
    $request->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
    ]);

    $newPass = Str::random(8);

    $user = User::create([
      'fullname' => $request->fullname,
      'email' => $request->email,
      'password' => Hash::make($newPass),
    ]);

    event(new Registered($user));

    return redirect()->route('users')
                      ->with('message', 'Usuario <'.$user->fullname.'> registrado correctamente.');
  }

  function index() {
    $data = [];
    $data['users'] = User::orderBy('created_at', 'DESC')->get();
    return Inertia::render('User/Index', $data);
  }

  function view() {}

  function update($id) {
    $data = [];
    $data['user'] = User::find($id);
    return Inertia::render('User/Form', $data);
  }

  function edit(Request $request, $id) {
    $request->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|string|email|max:255',
    ]);

    $user = User::find($id);
    $user->fullname = $request->fullname;
    $user->email = $request->email;
    $user->save();

    return redirect()->route('users')
                    ->with('message', 'Usuario <'.$user->fullname.'> actualizado correctamente.');
  }

  function delete($id) {

    $user = User::find($id);
    $user->status = $user->status===1? 0 : 1;
    $user->save();

    $statusStr = $user->status===1? 'activado' : 'bloqueado';

    return redirect()->route('users')
                    ->with('message', 'Usuario <'.$user->fullname.'> '.$statusStr.' correctamente.');
  }

}
