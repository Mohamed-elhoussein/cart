<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Notification;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    public function index()
    {
        $data=Wallet::all();
        if($data->isEmpty()){
            return response()->json(['message' => 'Users not Wallet'], 404); // إذا كانت المجموعة فارغة
        }
        return response()->json(["data"=>$data],200);
    }
    // إنشاء محفظة جديدة
    public function create(Request $request)
    {
        $user = User::findOrFail($request->userId);

        $wallet = new Wallet();
        $wallet->balance = $request->input('balance', 0); // الرصيد المبدئي
        $wallet->user_id = $request->userId;
        $wallet->save();
        $wallet["message"]="$request->balance تم اضافه مبلغ ";
        Notification::create([
            "user_id"=>$request->userId,
            "data"=>$wallet
        ]);
        return response()->json(["data"=>$wallet], 201);
    }

    // قراءة المحفظة الخاصة بالمستخدم
    public function show($userId)
    {
        $wallet = Wallet::where('user_id', $userId)->first();

        if ($wallet) {
            return response()->json($wallet);
        }

        return response()->json(['message' => 'Wallet not found'], 404);
    }

    // تحديث المحفظة (مثال: تعديل الرصيد)
    public function update(Request $request, $userId)
    {
        $wallet = Wallet::where('user_id', $userId)->first();

        if ($wallet) {
            $wallet->balance = $request->input('balance', $wallet->balance);
            $wallet->save();
            return response()->json($wallet);
        }

        return response()->json(['message' => 'Wallet not found'], 404);
    }

    // حذف المحفظة
    public function destroy($userId)
    {
        $wallet = Wallet::where('user_id', $userId)->first();

        if ($wallet) {
            $wallet->delete();
            return response()->json(['message' => 'Wallet deleted']);
        }

        return response()->json(['message' => 'Wallet not found'], 404);
    }
}
