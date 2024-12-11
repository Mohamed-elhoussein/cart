<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class notificationController extends Controller
{
    public function data ($id){
        $notification=Notification::where("user_id",$id)->get();

        return response()->json(["data"=>$notification],201);

    }
}
