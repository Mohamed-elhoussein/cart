<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class CarController extends Controller
{
    // إنشاء سيارة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'type' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'wallet_id' => 'required|exists:wallets,id',
        ]);

        $car = Car::create([
            'number' => $request->number,
            'type' => $request->type,
            'user_id' => $request->user_id,
            'wallet_id' => $request->wallet_id,
        ]);

        return response()->json($car, 201);
    }

    // عرض جميع السيارات
    public function index()
    {
        $cars = Car::all();
        if($cars->isEmpty()){
            return response()->json(["message"=>"not found cars"],404);
        }
        return response()->json($cars);
    }

    // عرض سيارة محددة
    public function show($id)
    {
        $car = Car::find($id);

        if ($car) {
            return response()->json($car);
        }

        return response()->json(['message' => 'Car not found'], 404);
    }

    // تحديث سيارة معينة
    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        if ($car) {
            $car->update([
                'number' => $request->number ?? $car->number,
                'type' => $request->type ?? $car->type,
                'user_id' => $request->user_id ?? $car->user_id,
                'wallet_id' => $request->wallet_id ?? $car->wallet_id,
            ]);
            return response()->json($car);
        }

        return response()->json(['message' => 'Car not found'], 404);
    }

    // حذف السيارة
    public function destroy($id)
    {
        $car = Car::find($id);

        if ($car) {
            $car->delete();
            return response()->json(['message' => 'Car deleted successfully']);
        }

        return response()->json(['message' => 'Car not found'], 404);
    }
}
