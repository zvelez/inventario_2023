<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DeliveryController extends Controller {
  
  function create($id) {
    $data = [];
    $data['delivery'] = new Delivery();
    $data['work'] = Work::find($id);
    return Inertia::render('Delivery/Form', $data);
  }

  function store(Request $request, $id) {
    $request->validate([
      'in' => 'nullable|boolean',
      'code' => 'required|string|max:255',
      'amount' => 'nullable|numeric',
      'deliverydate' => 'nullable|date',
      'estimatedate' => 'nullable|date',
      'responsible' => 'required|string|max:255',
      'contact' => 'nullable|string|max:255',
      'dnicontact' => 'nullable|string|max:255',
      'observations' => 'nullable|string',
    ]);

    $userId = Auth::id();
    $delivery = Delivery::create([
      'work_id' => $id,
      'in' => $request->in,
      'code' => $request->code,
      'amount' => $request->amount,
      'deliverydate' => $request->deliverydate,
      'estimatedate' => $request->estimatedate,
      'responsible' => $request->responsible,
      'contact' => $request->contact,
      'dnicontact' => $request->dnicontact,
      'observations' => $request->observations,
      'user_id' => $userId,
    ]);

    $work = Work::find($id);

    return redirect()->route('works.view', ['id' => $work->id])
      ->with('message', 'Entrega en fecha <'. $delivery->created_at->format('DD/MM/YYYY') .' para el trabajo Nro ' . $work->id .'> ha sido creado.');
  }

  function index() {
    $data = [];
    $data['works'] = Work::with(['client', 'deliveries', 'comments', 'products', 'products.supplies', 'products.manufacturer'])
          ->whereIn('status', [0, 1, 2])->get();
    //dd($data['work']->toArray());
    return Inertia::render('Delivery/Index', $data);
  } 
}
