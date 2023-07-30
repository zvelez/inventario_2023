<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller {
  
  function create() {}

  function index() {
    $data = [];
    $data['users'] = User::orderBy('created_at', 'DESC')->get();
    return Inertia::render('User/Index', $data);
  }

  function view() {}

  function update() {}

  function delete() {}

}
