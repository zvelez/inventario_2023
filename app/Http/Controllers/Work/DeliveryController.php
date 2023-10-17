<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DeliveryController extends Controller {
  
  function create($id) {
    $data = [];
    $data['delivery'] = new Delivery();
    $data['work'] = Work::with(['products', 'products.supplies'])->find($id);
    
    $data['items'] = [];
    foreach($data['work']->products as $prod) {
      $data['items'][] = ['label' => $prod->name, 'code' => $prod->code, 'in' => 0];
      foreach($prod->supplies as $sup) {
        $data['items'][] = ['label' => $sup->description.' - '. $sup->brand, 'code' => $sup->code, 'in' => 1];
      }
    }
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
  
  function update($wid, $id) {
    $data = [];
    $data['delivery'] = Delivery::find($id);
    $data['work'] = Work::with(['products', 'products.supplies'])->find($wid);
    
    $data['items'] = [];
    foreach($data['work']->products as $prod) {
      $data['items'][] = ['label' => $prod->name, 'code' => $prod->code, 'in' => 0];
      foreach($prod->supplies as $sup) {
        $data['items'][] = ['label' => $sup->description.' - '. $sup->brand, 'code' => $sup->code, 'in' => 1];
      }
    }
    return Inertia::render('Delivery/Form', $data);
  }

  function edit(Request $request, $wid, $id) {
    $request->validate([
      'in' => 'nullable|boolean',
      'code' => 'required|string|max:255',
      'amount' => 'nullable|numeric',
      'deliverydate' => 'nullable',
      'estimatedate' => 'nullable',
      'responsible' => 'required|string|max:255',
      'contact' => 'nullable|string|max:255',
      'dnicontact' => 'nullable|string|max:255',
      'observations' => 'nullable|string',
    ]);

    $userId = Auth::id();
    $delivery = Delivery::find($id);
    $delivery->amount = $request->amount;
    $delivery->deliverydate = $request->deliverydate==='0000-00-00' ? NULL : $request->deliverydate;
    $delivery->estimatedate = $request->estimatedate==='0000-00-00' ? NULL : $request->estimatedate;
    $delivery->responsible = $request->responsible;
    $delivery->contact = $request->contact;
    $delivery->dnicontact = $request->dnicontact;
    $delivery->observations = $request->observations;
    $delivery->save();

    $work = Work::find($wid);

    return redirect()->route('deliveries')
      ->with('message', 'Entrega <Nro '. $delivery->id .' para el trabajo Nro ' . $work->id .'> ha sido actualizado.');
  }

  function index() {
    $data = [];
    $data['works'] = Work::with(['client', 'deliveries', 'comments', 'products', 'products.supplies', 'products.manufacturer'])
          ->whereIn('status', [0, 1, 2])->orderBy('deadline', 'ASC')->get();
    //dd($data['work']->toArray());
    return Inertia::render('Delivery/Index', $data);
  } 
}
