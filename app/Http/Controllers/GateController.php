<?php

namespace App\Http\Controllers;

use App\Models\gate;
use Illuminate\Http\Request;

class GateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gate=gate::get();
        if($gate->isEmpty()){
            return response()->json(["message"=>"not found gate"],404);
        }
        return response()->json(["data"=>$gate],200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'gate_number' => 'required|integer',
            'location' => 'required|string|max:255',
            'ticket_price' => 'required|numeric',
        ]);

        $gate = gate::create([
            'gate_number' => $request->gate_number,
            'location' => $request->location,
            'ticket_price' => $request->ticket_price,
        ]);

        return response()->json(["data"=>$gate], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gate = gate::findOrFail($id);
        return response()->json(["data"=>$gate],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gate = gate::findOrFail($id);

        $request->validate([
            'gate_number' => 'required|integer',
            'location' => 'required|string|max:255',
            'ticket_price' => 'required|numeric',
        ]);

        $gate->update([
            'gate_number' => $request->gate_number,
            'location' => $request->location,
            'ticket_price' => $request->ticket_price,
        ]);

        return response()->json(["gate"=>$gate],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gate = gate::findOrFail($id);
        $gate->delete();
        return response()->json(['message' => 'Gate deleted successfully']);
    }
}
