<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserController extends Controller {
  
  function create() {
    $has_perm = RoleController::checkPermission(['ADMSYS', 'ADMIN']);
    if(!$has_perm) {
      return redirect()->route('dashboard')->withErrors(['NO_PERM' => 'No tiene permisos para acceder a la página anterior.']);
    }
    $data = [];
    $data['user'] = new User();
    $data['roles'] = Role::get();
    return Inertia::render('User/Form', $data);
  }

  function store(Request $request) {
    $has_perm = RoleController::checkPermission(['ADMSYS', 'ADMIN']);
    if(!$has_perm) {
      return redirect()->route('dashboard')->withErrors(['NO_PERM' => 'No tiene permisos para realizar la acción solicitada.']);
    }
    $request->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'role_id' => 'required|integer',
    ]);

    $newPass = Str::random(8);

    $user = User::create([
      'fullname' => $request->fullname,
      'email' => $request->email,
      'role_id' => $request->role_id,
      'password' => Hash::make($request->email),
      //'password' => Hash::make($newPass),
    ]);

    event(new Registered($user));

    return redirect()->route('users')
                      ->with('message', 'Usuario <'.$user->fullname.'> registrado correctamente.');
  }

  function index() {
    $has_perm = RoleController::checkPermission(['ADMSYS', 'ADMIN']);
    if(!$has_perm) {
      return redirect()->route('dashboard')->withErrors(['NO_PERM' => 'No tiene permisos para acceder a la página anterior.']);
    }
    $data = [];
    $data['users'] = User::with(['role'])->orderBy('created_at', 'DESC')->get();
    return Inertia::render('User/Index', $data);
  }

  function view() {}

  function update($id) {
    $has_perm = RoleController::checkPermission(['ADMSYS', 'ADMIN']);
    if(!$has_perm) {
      return redirect()->route('dashboard')->withErrors(['NO_PERM' => 'No tiene permisos para acceder a la página anterior.']);
    }
    $data = [];
    $data['user'] = User::find($id);
    $data['roles'] = Role::get();
    return Inertia::render('User/Form', $data);
  }

  function edit(Request $request, $id) {
    $has_perm = RoleController::checkPermission(['ADMSYS', 'ADMIN']);
    if(!$has_perm) {
      return redirect()->route('dashboard')->withErrors(['NO_PERM' => 'No tiene permisos para realizar la acción solicitada.']);
    }
    $request->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|string|email|max:255',
      'role_id' => 'required|integer',
    ]);

    $user = User::find($id);
    $user->fullname = $request->fullname;
    $user->email = $request->email;
    $user->role_id = $request->role_id;
    $user->save();

    return redirect()->route('users')
                    ->with('message', 'Usuario <'.$user->fullname.'> actualizado correctamente.');
  }

  function delete($id) {
    $has_perm = RoleController::checkPermission(['ADMSYS', 'ADMIN']);
    if(!$has_perm) {
      return redirect()->route('dashboard')->withErrors(['NO_PERM' => 'No tiene permisos para realizar la acción solicitada.']);
    }
    $user = User::find($id);
    $user->status = $user->status===1? 0 : 1;
    $user->save();

    $statusStr = $user->status===1? 'activado' : 'bloqueado';

    return redirect()->route('users')
                    ->with('message', 'Usuario <'.$user->fullname.'> '.$statusStr.' correctamente.');
  }

  function profile() {
    $data = [];
    $data['user'] = Auth::user();
    $data['roles'] = null;
    return Inertia::render('User/Form', $data);
  }

  function password() {
    $data = [];
    $data['user'] = Auth::user();
    return Inertia::render('User/Password', $data);
  }

  function password_save(Request $request) {
    $request->validate([
      'password' => 'required|string|max:255',
      'repeat' => 'required|string|max:255',
    ]);

    if($request->password != $request->repeat) {
      return back()->withErrors('Las contraseñas no coindiden.');
    }

    $user = User::find(Auth::id());
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('dashboard')
                    ->with('message', 'La contraseña ha sido actualizada correctamente.');
  }

}
