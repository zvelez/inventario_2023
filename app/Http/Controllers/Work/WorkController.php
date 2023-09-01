<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Work;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkController extends Controller {

  private $status = [
    'Iniciado',
    'Producción',
    'Revisión',
    'Entregable',
    'Entregado',
  ];
  
  function create() {
    $data = [];
    $data['work'] = new Work();
    $data['statuslist'] = $this->status;
    return Inertia::render('Work/Form', $data);
  }

  function store(Request $request) {
    $request->validate([
      'deadline' => 'required|date',
      'status' => 'required|string|max:255',
      'client_id' => 'required',
    ]);

    $work = Work::create([
      'deadline' => $request->deadline,
      'status' => $request->status,
      'client_id' => $request->client_id,
    ]);

    $client = Client::find($request->client_id);

    return redirect()->route('works.add', ['wid' => $work->id])
      ->with('message', 'Trabajo en fecha <'. $work->created_at->format('DD/MM/YYYY') .' para el cliente ' . $client->fullname .'> ha sido creado.');
  }

  function index() {
    $data = [];
    $data['works'] = Work::with(['client'])->orderBy('deadline', 'DESC')->get();
    $data['statuslist'] = $this->status;
    return Inertia::render('Work/Index', $data);
  }

  function view($id) {
    $data = [];
    $data['work'] = Work::with(['client', 'deliveries', 'comments', 'products', 'products.supplies', 'products.manufacturer'])->find($id);
    //dd($data['work']->toArray());
    return Inertia::render('Work/View', $data);
  }
  
  function update($id) {
    $data = [];
    $data['work'] = Work::with(['client'])->find($id);
    $data['statuslist'] = $this->status;
    return Inertia::render('Work/Form', $data);
  }

  function edit(Request $request, $id) {
    $request->validate([
      'deadline' => 'required|string|max:255',
      'status' => 'required|string|max:255',
      'client_id' => 'required',
    ]);

    $work = Work::find($id);
    $work->deadline = $request->deadline;
    $work->status = $request->status;
    $work->client_id = $request->client_id;
    $work->save();

    $client = Client::find($request->client_id);

    return redirect()->route('work-progress')
      ->with('message', 'Trabajo <'. $work->created_at .' para el cliente ' . $client->fullname .'> ha actualizado.');
  }

  function filters(Request $request) {
    $request->validate([
      'status' => 'required|string',
    ]);
    
    $orders = Work::orderBy('deadline', 'DESC')->with(['client']);
    if($request->status != 'Todos') {
      $orders = $orders->where('status', '=', $request->status);
    }

    $orders = $orders->get();
    return response()->json($orders);
  }
}
