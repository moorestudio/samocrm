<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function setUserTab(Request $request){
        session(['userTab' => $request->tab]);
        return response()->json([
            'status'=>'success',
            'session' => session()->get('userTab'),
        ],200);
    }
    public function setClientTab(Request $request){
        session(['clientTab' => $request->tab]);
        return response()->json([
            'status'=>'success',
            'session' => session()->get('clientTab'),
        ],200);
    }


}
