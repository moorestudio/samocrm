<?php

namespace App\Http\Controllers;
use App\Information;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Event;
use App\Financial;
use App\Hall;
use App\User;
use App\Tilda;
use App\ReferralLink;
use App\ReferralRelationship;
use App\CurrencyNames;
use Session;
use Auth;

class EventController extends Controller
{

    public function index(){
        $user_id = Auth::user()->id;
        $events = Event::where('user_id',$user_id)->get();
        // dd($events);
        return view('event.event_dashboard',['events' => $events]);
    }

    public function event_list(Request $request){
        $id = Auth::user()->id;
        $reff_links_event_id_url = ReferralLink::all()->where('user_id',$id)->pluck('url_link','referral_event_id');
        $reff_links_event_ids = ReferralLink::all()->where('user_id',$id)->pluck('referral_event_id');
//        dd($reff_links_event_id_url);
        $events = Event::where('active',1)->where('date', '>' , Carbon::now())->get()->sortByDesc('created_at');
        $events_with_links = Event::find($reff_links_event_ids);
//        dd($events_with_links);
        $diff = $events->diff($events_with_links);

//        $events_with_links=[];
        foreach ($events_with_links as $event)
            foreach ($reff_links_event_id_url as $key => $value)
                if ($event->id == $key)
                    $event['link_new']=$value;

//         dd($events_with_links);
        $all_all=$events_with_links->merge($diff);
        $current_user_events=[];
        $category = 0;
        if($request->id){
          $events = $events->where('category_id',$request->id);
          $category = $request->id;
        }
        foreach ($all_all as $eve){
            if($eve->user_id == Auth::user()->id && Auth::user()->role_id == 6){
                array_push($current_user_events, $eve);
            }
        }
        return view('event.event_dashboard',['events' => $all_all,'current_user_events'=>$current_user_events,'category'=>$category]);
    }

    public function change_status(Request $request){
        $event = Event::find($request->event);
        if ($request->event_status == "true") {
            $event->active = 1;
        } else {
            $event->active = 0;
        }
        $event->save();

        return response()->json([
                'status' => $event->active,
        ], 200);
    }
    public function show(Request $request, $id)
    {

        // if ($request->has('ref')){
        //     $referral = ReferralLink::whereCode($request->get('ref'))->first();
        //     $referrer_id = $referral->user_id;

        //     if(Auth::check() && Auth::user()->role_id == 2 && Auth::user()->franchise_id != null){
        //         if(Auth::user()->franchise_id != $referrer_id){
        //             // dd('NOT SAME');
        //             $time = 1 * 24 * 60; //1 день
        //             Session::put('check_sales', $referrer_id);
        //         }
        //         else{
        //             Session::forget('check_sales');
        //         }
        //     }

        // }

        $event = Event::find($id);
        $information = Information::where('event_id',$event->id)->first();
        $row = Hall::where('event_id', $id)->count();
        $column = Hall::where('event_id', $id)->first();
        $halls = Hall::where('event_id', $id)->get();
        if ($column) {
            $collect = collect($column->column);
            $count = $collect->count();
        } else {
            $count = null;
        }
        $data = [
            'halls' => $halls,
            'event' => $event,
            'row' => $row,
            'column' => $count,
            'information' => $information,
            'url'=>'testUrl'
        ];
        if($event->active == 0)
        {
            return redirect()->route('main');
        }
        else
        {
            if($event->tilda_pageid){
              $view = 'tilda_templates/'.$event->tilda_pageid.'/main';
              return view('tilda_templates.tilda')->with('data',$data)->with('view',$view);
            }else{
              return view('event.info', $data);
            }
        }
    }

    public function create(Request $request)
    {
        if (isset($request->event)) {
            $event = Event::find($request->event);
            return view('admin.events.create', ['event' => $event]);
        } else {
            return view('admin.events.create');
        }
    }

    function get_rates($request){
        $k = array();
        $num_n =0;
        $num_c=0;
        $num_p=0;
        $num_pd=0;
        $num_pp=0;
        $n_c = [];
        $q=0;
        $rate_numbers =[];
        $request_array = $request->all();
        $request_array += [
            "rate_name100" => "lytsok",
            "rate_price100" => "lytsok",
            "rate_color100" => "lytsok",
            "promo_date100" => "lytsok",
            "promo_price100" => "lytsok",

        ];
        function moveElement(&$array, $a, $b) {
            $out = array_splice($array, $a, 1);
            array_splice($array, $b, 0, $out);
        }
        foreach($request_array as $key => $value) {

            if(substr($key, 0,9)=='rate_name'){
                $length  = strlen($key) - 9;
                array_push($rate_numbers, substr($key, 9,$length));
            }
        }

        $rate_numbers_count = count($rate_numbers)-1;
        foreach($request_array as $key => $value) {


            $name ='';
            $color ='';
            $price ='';
            $promo_date ='';
            $promo_price ='';
            // dump($key =='rate_name'.$num);
            // dump($key,'rate_name'.$num);

            if($key =='rate_name'.$rate_numbers[$num_n]){
                // dump($rate_numbers[$num_n]);
                array_push($n_c, $value);
                $name=$value;
                // dump($n_c);
                if($rate_numbers_count>$num_n){
                    $num_n ++;
                }
            }

            elseif($key=='rate_color'.$rate_numbers[$num_c]){
                $color=$value;
                array_push($n_c, $value);
                if($rate_numbers_count>$num_c){
                    $num_c ++;
                }
            }

            elseif($key=='rate_price'.$rate_numbers[$num_p]){
                $price=$value;
                array_push($n_c, $value);
                if($rate_numbers_count>$num_p){
                    $num_p ++;
                }
            }

            elseif($key=='promo_date'.$rate_numbers[$num_pd]){
                $promo_date=$value;
                array_push($n_c, $value);
                if($rate_numbers_count>$num_pd){
                    $num_pd ++;
                }
            }

            elseif($key=='promo_price'.$rate_numbers[$num_pp]){
                $promo_price=$value;
                array_push($n_c, $value);
                if($rate_numbers_count>$num_pp){
                    $num_pp ++;
                }
            }

            $nummm = count($rate_numbers);
            // dump($num_n,'>',$q);
            if($num_n>$q && $num_c>$q && $num_p>$q && $num_pd>$q && $num_pp>$q){
            // dump($n_c);
             moveElement($n_c, 2, 1);
             array_push($k,$n_c);
             // dump($n_c);
             $q++;

             $n_c = [];

            }

        }
        return $k;
    }
    public function store(Request $request)
    {
        $event_rates = $this->get_rates($request);
        $event = new Event();

        if(Auth::user()->role_id == 6){
            $event->user_id = Auth::user()->id;
            $franchise = Auth::user();
        }
        else{
           $event->user_id = $request->user_id;
           $franchise = User::find($request->user_id);
        }

        if ($franchise->role_id == 4) {
            $franchise->role_id = 6;
            $franchise->save();
        }
        if (!$franchise->event_rights) {
            $franchise->event_rights = 1;
            $franchise->save();
        }



        $event->category_id = $request->category_id;
        $event->title = $request->name;
        $event->date = $request->date;
        $event->normal_date = Carbon::parseFromLocale($request->date,'ru')->format('Y-m-d h:i:s');
        $event->end_date = $request->end_date;
        $event->city = $request->city;
        $event->address = $request->address;
        $event->latitude = $request->latitude;
        $event->longtitude = $request->longtitude;

        $event->rate =$event_rates;

        $event->currency = $request->currency;
        $event->mentor = $request->mentor;
        $event->buy_deadline = $request->buy_date;
        $event->reserve_date = $request->reserve_date;
        $event->newsletter_time = $request->newsletter_time;
        $event->yandex_shop_id = $request->yandex_shop_id;
        $event->yandex_secret_key = $request->yandex_secret_key;
        $event->paybox_merchant_id = $request->paybox_merchant_id;
        $event->paybox_secret_key = $request->paybox_secret_key;
        $event->convert_rub = $request->convert_rub;
        if ($request->scheme == 'on') {
            $event->scheme = 0;
            $event->ticket_count = $request->ticket_count;
        } else {
            $event->scheme = 1;
            $event->ticket_count = $request->client_count;
        }
        $event->franch_count = $request->franch_count;
        $event->client_count = $request->client_count;
        if ($request->active == 'on') {
            $event->active = 1;
        } else {
            $event->active = 0;
        }
        if ($file = $request->file('preview')) {
            $name = "events/" . 'eventertype' . rand(1, 1400) . $event->id . '.png';
            if ($file->move('storage/events', $name)) {
                $event->image = mb_strtolower($name); //remane img_path
                $event->save();
            }
        }
        $event->save();

        $financial = new Financial();
        $financial->event_id = $event->id;

        $financial->franch_perc = $request->franch_perc ? $request->franch_perc : 0;
        $financial->partner_perc = $request->partner_perc ? $request->partner_perc : 0;
        $financial->samo_sales_perc = $request->samo_sales_perc ? $request->samo_sales_perc : 0;
        $financial->total_rawIncome = 0;
        $financial->save();
        if(Auth::user()->role_id == 6){
          // if something does not work gotta check
          // return redirect("event.list/".Auth::user()->id);
            return redirect("event.list");
        }
        return redirect()->route('admin');
    }

    public function update(Request $request,$event ){
        $event = Event::find($event);
        $franchise = User::find($event->user_id);

        $franch_events = 0;

        // if ($franchise->id != $request->user_id) {
        //     foreach (Event::all() as $item) {
        //         if ($item->user_id == $franchise->id) {
        //             $franch_events++;
        //         }
        //     }
        //     if ($franch_events >= 1 && $franchise->role_id != 3) {
        //         $franchise->role_id = 6;
        //     }
        // }
        // $franchise->save();
        if ($franchise->role_id == 4) {
            $franchise->role_id = 6;
            $franchise->save();
        }
        if ($franchise->event_rights == null) {
            $franchise->event_rights = 1;
            $franchise->save();
        }

        $event->user_id = $request->user_id;
        $event->category_id = $request->category_id;
        $event->title = $request->name;
        $event->date = $request->date;
        $event->normal_date = Carbon::parseFromLocale($request->date,'ru')->format('Y-m-d h:i:s');
        $event->end_date = $request->end_date;
        $event->city = $request->city;
        $event->address = $request->address;
        $event->latitude = $request->latitude;
        $event->longtitude = $request->longtitude;
        $event_rates = $this->get_rates($request);
        $event->rate =$event_rates;
        $event->currency = $request->currency;
        $event->mentor = $request->mentor;
        $event->buy_deadline = $request->buy_date;
        $event->reserve_date = $request->reserve_date;
        $event->newsletter_time = $request->newsletter_time;
        $event->yandex_shop_id = $request->yandex_shop_id;
        $event->yandex_secret_key = $request->yandex_secret_key;
        $event->paybox_merchant_id = $request->paybox_merchant_id;
        $event->paybox_secret_key = $request->paybox_secret_key;
        $event->convert_rub = $request->convert_rub;
        if ($request->scheme == 'on')
        {
            $event->scheme = 0;
            $event->ticket_count = $request->ticket_count;
        }
        else
        {
            $event->scheme = 1;
            $event->ticket_count = $request->client_count;
        }
        if(isset($request->franch_count)){
        $event->franch_count = $request->franch_count;
        }
        if(isset($request->client_count)){
        $event->client_count = $request->client_count;
        }
        if ($request->active == 'on')
        {
            $event->active = 1;
        }
        else
        {
            $event->active = 0;
        }
        if ($file = $request->file('preview')) {
            $name = "events/" . 'eventertype'. rand(1,1400) . $event->id . '.png';// нужно было / добавить event/
            if ($file->move('storage/events', $name)) {
                $event->image = mb_strtolower($name);//rename img_path
                $event->save();
            }
        }
        $event->save();

        $financial = $event->financial;
        $financial->franch_perc = $request->franch_perc ? $request->franch_perc : 0;
        $financial->partner_perc = $request->partner_perc ? $request->partner_perc : 0;
        $financial->samo_sales_perc = $request->samo_sales_perc ? $request->samo_sales_perc : 0;
        $financial->total_rawIncome = 0;
        $financial->save();

        if(Auth::user()->role_id == 6){
            // if something not work need to check
            // return redirect("event.list/".Auth::user()->id);
            return redirect("event.list");

        }
        return redirect()->route('admin');
        // return redirect()->route('event_list');
    }

    public function info_create(Request $request)
    {
        $event = Event::find($request->event);

        $information = Information::where('event_id',$event->id)->first();

        return view('admin.events.info_create',['event' => $event, 'information' => $information]);
    }

    public function info_store(Request $request)
    {
        $event = Event::find($request->event_id);

        $information = Information::where('event_id',$event->id)->first();
        if(!$information)
        {
            $information = new Information();
        }
        $information->event_id = $event->id;
        $information->first_block = $request->first_block;
        $information->second_block = $request->second_block;
        $information->third_block = $request->third_block;
        $information->fourth_block = $request->fourth_block;
        $information->fifth_block = $request->fifth_block;
        $information->save();
        if(Auth::user()->role_id == 6){
            return redirect("event.list/".Auth::user()->id);
        }
        return redirect('admin');
    }





    public function check_active()
    {

        $events = Event::where('active',1)->get();

        foreach ($events as $event)
        {
            if(Carbon::parseFromLocale($event->date, 'ru') < Carbon::now())
            {
                $event->end = 1;
                $event->save();
            }

        }

        $events = Event::where('end',1)->get();

        foreach ($events as $event)
        {
            if(Carbon::parseFromLocale($event->date, 'ru') > Carbon::now())
            {
                $event->end = null;
                $event->save();
            }
        }
    }
    public function tilda_create($id){
      $event = Event::find($id);
      $tilda = Tilda::all()->first();
      return view('admin.events.tilda_create',['event' => $event,'tilda' => $tilda]);
    }
    public function tilda_store(Request $request){
        $event = Event::find($request->id);
        $url_main = 'http://api.tildacdn.info/v1/getpagefullexport/?';
        $data_main=array(
        		'publickey' =>$request->publickey,
        		'secretkey' => $request->secretkey,
            'pageid' => $request->pageid,
        );
        $url_main = $url_main.http_build_query($data_main);
        $ch = curl_init($url_main);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response_json = curl_exec($ch);
        curl_close($ch);
        $response=json_decode($response_json, true);
        $main_dir = "storage/tilda_templates";
        $index_dir = "../resources/views/tilda_templates/".$response['result']['id'];
        if(!is_dir($main_dir)){
          mkdir($main_dir, 0700);
        }
        if(!is_dir($index_dir)){
          mkdir($index_dir, 0700);
        }
        if(!is_dir($main_dir.'/js')){
          mkdir($main_dir.'/js', 0700);
        }
        if(!is_dir($main_dir.'/css')){
          mkdir($main_dir.'/css', 0700);
        }
        if(!is_dir($main_dir.'/images')){
          mkdir($main_dir.'/images', 0700);
        }
        foreach($response['result']['css'] as $css ){
          $file = $css['from'];
          $newfile = $main_dir.'/css/'.$css['to'];
          copy($file, $newfile);
        };
        foreach($response['result']['js'] as $js ){
          $file = $js['from'];
          $newfile = $main_dir.'/js/'.$js['to'];
          copy($file, $newfile);
        };
        foreach($response['result']['images'] as $image ){
          $file = $image['from'];
          $newfile = $main_dir.'/images/'.$image['to'];
          copy($file, $newfile);
        };
        $html = fopen($index_dir."/main.blade.php", "w") or die("Unable to open file!");
        $txt = $response['result']['html'];
        fwrite($html, $txt);
        fclose($html);
        $event->tilda_publickey = $data_main['publickey'];
        $event->tilda_secretkey = $data_main['secretkey'];
        $event->tilda_pageid = $data_main['pageid'];
        $event->save();
        return redirect()->back();
    }
}
