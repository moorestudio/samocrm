<?php

namespace App\Http\Controllers;

use App\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        return view('admin.promocode.list',['promos' => PromoCode::all()]);
    }

    public function create()
    {
        return view('admin.promocode.create');
    }

    public function edit(Request $request)
    {
        $promo = PromoCode::find($request->id);

        return view('admin.promocode.create',['promo' => $promo]);
    }

    public function store(Request $request)
    {
        $promo = new PromoCode();
        $promo->name = $request->name;
        $promo->promo = $request->promo;
        $promo->discount = $request->discount;
        if ($request->active == 'on') {
            $promo->active = true;
        } else {
            $promo->active = false;
        }

        $promo->save();

        return redirect()->route('promo_list');
    }

    public function update(Request $request)
    {
        $promo = PromoCode::find($request->id);
        $promo->name = $request->name;
        $promo->promo = $request->promo;
        $promo->discount = $request->discount;
        if ($request->active == 'on') {
            $promo->active = true;
        } else {
            $promo->active = false;
        }

        $promo->save();

        return redirect()->route('promo_list');
    }

    public function check_promo(Request $request)
    {
        $promos = PromoCode::where('active',1)->get();
        $user_promo = PromoCode::where('promo',$request->promo)->first();
        $check = false;

        if ($user_promo){
          foreach ($promos as $promo){
              if ($user_promo->id == $promo->id)
              {
                  $check = true;
              }
          }
        }

        if ($request->ajax()){
            return response()->json([
                'status' => "success",
                'check' => $check,
                'promo' => $user_promo
            ], 200);
        }
    }
}
