<?php

namespace App\Http\Controllers;

use App\Event;
use App\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $events = Event::all();
        $categories = Category::all();
        $id = 0;
        if($request->id){
          $events = $events->where('category_id',$request->id)->where('active',1);
          $id =$request->id;
        }
        return view('welcome', ['events' => $events,'categories' => $categories,'id'=>$id]);
    }
    public function main(){
        if(auth()->check()){
            if(auth()->user()->role_id == 3){
                return redirect('/admin');
            }
            if(auth()->user()->role_id == 6 or auth()->user()->role_id == 5){
                return redirect('/event.list');
            }
        }
        $events = Event::where('active',1)->get();
        $categories = Category::all();
        $id = 0;
        return view('welcome', ['events' => $events,'categories' => $categories,'id'=>$id]);
    }

}
