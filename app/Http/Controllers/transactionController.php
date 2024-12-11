<?php

namespace App\Http\Controllers;

use App\Models\gate;
use App\Models\Wallet;
use App\Models\transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class transactionController extends Controller
{
    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'gate_id' => 'required|exists:gates,id',
        ]);

        $wallet = Wallet::where("user_id",$validated['user_id'])->first();
        $balance=$wallet["balance"];
        $gate = gate::find($validated['gate_id']);
        if (!$wallet) {
        return response()->json(['message' => 'Wallet not found'], 404);
        }
        // التحقق من أن المحفظة تحتوي على رصيد كافٍ
        if ($balance < $gate['ticket_price']) {
            return response()->json(['message' => 'Insufficient balance in wallet'], 400);
        }

        // استخدام معاملة قاعدة البيانات لضمان التحديث الصحيح
        DB::beginTransaction();

        try {
            // طرح المبلغ من رصيد المحفظة
            $wallet->balance -= $gate['ticket_price'];
            $wallet->save();
            $transaction = Transaction::create([
                'user_id' => $validated['user_id'],
                'gate_id' => $validated['gate_id'],
                'wallet_id' => $wallet->id,  // إضافة wallet_id هنا
                "amount"=>$gate['ticket_price'],
                "transaction_type"=>"دفع"
            ]);
            $transaction["message"]="تكلفه البوابه $gate->ticket_price تم خصم مبلغ ";
            $transaction["balance"]=$balance;

            $data[]=$transaction;
            Notification::create([
                "user_id"=>$validated['user_id'],
                "data"=>$transaction
            ]);

            // تأكيد المعاملة
            DB::commit();

            return response()->json(['message' => 'Transaction created successfully', 'transaction' => $transaction], 201);
        } catch (\Exception $e) {
            // في حال حدوث خطأ، نقوم بالتراجع عن التحديثات
            DB::rollBack();

            return response()->json(['message' => 'Transaction failed', 'error' => $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Transaction created successfully', 'transaction' => $transaction], 201);
    }

        // إرجاع استجابة بنجاح


    // 2. الحصول على كل المعاملات (Read)
    public function index()
    {
        // استرجاع كل المعاملات
        $transactions = transaction::all();

        // إرجاع المعاملات في استجابة
        if($transactions->isEmpty()){
            return response()->json(["message"=>"No transactions"],404);

        }
        return response()->json($transactions);
    }

    // 3. الحصول على معاملة معينة (Read)
    public function show($id)
    {
        // العثور على المعاملة باستخدام الـ id
        $transaction = transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // إرجاع المعاملة
        return response()->json($transaction);
    }

    // 4. تحديث معاملة (Update)
    public function update(Request $request, $id)
    {
        // العثور على المعاملة
        $transaction = transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'gate_id' => 'sometimes|required|exists:gates,id',
            'wallet_id' => 'sometimes|required|exists:wallets,id',
            'amount' => 'sometimes|required|numeric',
            'transaction_type' => 'sometimes|required|string',
        ]);

        // تحديث المعاملة
        $transaction->update($validated);

        // إرجاع استجابة بنجاح
        return response()->json(['message' => 'Transaction updated successfully', 'transaction' => $transaction]);
    }

    // 5. حذف معاملة (Delete)
    public function destroy($id)
    {
        // العثور على المعاملة
        $transaction = transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // حذف المعاملة
        $transaction->delete();

        // إرجاع استجابة بنجاح
        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}
