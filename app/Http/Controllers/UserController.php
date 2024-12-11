<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // عرض جميع المستخدمين
    public function index()
    {
            $users = User::all();
            if ($users->isEmpty()) {
                return response()->json(['message' => 'Users not found'], 404); // إذا كانت المجموعة فارغة
            }
            return response()->json($users);
    }

    // عرض مستخدم معين
    public function show($id)
    {
        $user = User::find($id); // البحث عن المستخدم باستخدام المعرف
        if ($user) {
            return response()->json($user); // إرجاع بيانات المستخدم
        }
        return response()->json(['message' => 'User not found'], 404); // إذا لم يتم العثور على المستخدم
    }

    // إضافة مستخدم جديد
    public function store(Request $request)
    {

        // التحقق من البيانات المدخلة
        $request->validate([
            'name' => 'required|string|max:255',
            'national_number' => 'required|unique:users,National_number',
            'password' => 'required|string|min:8',
        ]);

        // إنشاء مستخدم جديد باستخدام البيانات المدخلة
        $user = User::create([
            'name' => $request->name,
            'National_number' => $request->national_number,
            'password' => $request->password, // تشفير كلمة السر
        ]);

        return response()->json(["data"=>$user], 201); 
    }

    // تحديث بيانات مستخدم
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            // التحقق من البيانات المدخلة وتحديث المستخدم
            $user->update($request->all());
            return response()->json($user);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    // حذف مستخدم
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete(); // حذف المستخدم
            return response()->json(['message' => 'User deleted successfully']);
        }
        return response()->json(['message' => 'User not found'], 404);
    }
}

