<?php

namespace App\Http\Controllers;
use App\Mail\Pdfsend;
use App\Mail\SendNotificationAboutReserve;
use Barryvdh\DomPDF\Facade as PDF;
use App\Count_buys;
use App\Event;
use App\CurrencyNames;
use App\EventFranchFind;
use App\Financial;
use App\PromoCode;
use App\Ticket;
use App\TicketDesign;
use App\User;
use App\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Hall;
use App\OutHall;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use YandexCheckout\Client;
use Illuminate\Support\Str;

class BuyController extends Controller
{

    public function order(Request $request)
    {
        $row = $request->row_number;
        $place = $request->place_number;
        $event = Event::find($request->event_id);

        return view('pages.order',['row' => $row, 'place' => $place, 'event' => $event]);
    }

    public function buy(Request $request, $id)
    {

        $event = Event::find($id);
//        $img_x = explode("#", $event->speaker_position)[0];
//        $img_y = explode("#", $event->speaker_position)[1];
        $row = Hall::where('event_id', $id)->count();
        $column = Hall::where('event_id', $id)->first();
        $outrow = OutHall::where('event_id', $id)->count();
        $halls = Hall::where('event_id', $id)->get();
        $out_halls =OutHall::where('event_id', $id)->get();
        if ($column) {
            $collect = collect($column->column);
            $count = $collect->count();
            $out_width = OutHall::where('event_id', $id)->first()->width;
            $out_height = OutHall::where('event_id', $id)->first()->height;
        } else {
            $count = null;
            $out_width=null;
            $out_height = null;
        }
//        $data = [
//            'event' => $event,
//            'row' => $row,
//            'column' => $count,
//            'halls' => $halls,
//        ];
        return view('event.scene', ['event' => $event, 'row' => $row, 'column' => $count, 'halls' => $halls, 'type' => 'buy', 'out_halls' => $out_halls, 'out_height' => $out_height, 'out_width' => $out_width, 'outrow' => $outrow]);
    }

    public function buy_page(Request $request){
        $event = Event::find($request->id);
        $row = $request->row;
        $column = $request->column;
        $price = $request->price;
        $type = $request->type;
        $ticket_id = null;
        $format_id = $request->format_id;
        $promo = $request->promo;
        $promotion = $request->promotion;
        $with = $price;
        if($promo){
          $promocode = PromoCode::where('promo',$promo)->first();
          if($promocode){
            $with = intval($promotion - (($promotion/100)*$promocode->discount));
          }
        }
        if (isset($request->ticket)){
            // $format_id = Ticket::find($request->id)->ticket_format;
            $format_id = Ticket::find($request->ticket)->ticket_format;
            $ticket_id = $request->ticket;
        }

        $user = Auth::user();
        // dd($format_id);
        return view('event.buy',[
            'event' => $event,
            'row' => $row,
            'column' => $column,
            'price' => $price,
            'promo' => $promo,
            'promotion' => $promotion,
            'type' => $type,
            'user' => $user,
            'with' => $with,
            'ticket_id' => $ticket_id,
            'format_id' => $format_id,
        ]);
    }

    public function buy_first(Request $request){
        // dd($request,"buy_first");
        $event = Event::find($request->id);
        $event_currency = \App\CurrencyNames::find($event->currency)->currency;
        $ticketDesign = TicketDesign::where('event_id', $event->id)->first();
        $user = Auth::user();
        $price = $request->price;
        $with = $price;
        if($request->promo){
          $promo = PromoCode::where('promo',$request->promo)->first();
          if($promo){
            $with = intval($request->promotion - (($request->promotion/100)*$promo->discount));
          }
        }
        return view('event.buy2',[
            'event' => $event,
            'price' => $price,
            'row' => $request->row,
            'column' => $request->column,
            'city' => $request->city,
            'country' => $request->country,
            'work' => $request->work,
            'type' => $request->type,
            'show' => $request->show,
            'agree' => $request->agree,
            'promo' => $request->promo,
            'promotion' => $request->promotion,
            'found' => $request->found,
            'ticket_id' => $request->ticket,
            'format_id' => $request->format_id,
            'ticketDesign' => $ticketDesign,
            'user' => $user,
            'with' => $with,
            'event_currency'=>$event_currency,
        ]);
    }

    public function buy_ticket(Request $request)
    {
        if(Auth::user()->role_id == 3 || Auth::user()->role_id == 6 && $request->who=="admin" ){
            $ticket_user_id = $request->user_id;
        }
        elseif(Auth::user()->role_id == 2){
            $ticket_user_id = Auth::id();
        }
        else{
            return response()->json([
                'status' => "Cant buy",
            ], 200);
        }




        $check_buy = Ticket::where('user_id', $ticket_user_id)->where('event_id',$request->event_id)->where('type','buy')->get()->first();

        if(isset($check_buy))
        {
            if ($request->ajax()){
                return response()->json([
                    'status' => "error",
                    'check' => 0,
                ], 200);
            }
        }
        else {
            $check_reserve = Ticket::where('user_id', $ticket_user_id)->where('event_id', $request->event_id)->where('type', 'reserve')->get()->first();
            if (isset($check_reserve) && $request->ticket == null)
            {
                if ($request->ajax()){
                    return response()->json([
                        'status' => "error",
                        'check' => 1,
                    ], 200);
                }
            }
            else
            {
                $event = Event::find($request->event_id);
                if ($event->scheme == 1) {
                    $halls = Hall::where('event_id', $request->event_id)->get();
                    foreach ($halls as $hall) {
                        if ($hall->row == $request->row) {
                            if ($request->ticket != null)
                            {
                                $ticket = Ticket::find($request->ticket);
                            }
                            else
                            {
                                $ticket = new Ticket();
                            }

                            $ticket->user_id = $ticket_user_id;
                            $ticket->event_id = $request->event_id;
                            $ticket->ticket_format = $hall->column[$request->column]['status'];
                            $promo = PromoCode::where('promo',$request->promo)->first();
                            if($promo){
                              $ticket->ticket_price  =$request->promotion - (($request->promotion / 100) * $promo->discount);
                              $ticket->discount = (($request->promotion / 100) * $promo->discount);
                              $ticket->promo_id = $promo->id;
                              $ticket->promo_name = $promo->name;
                              $ticket->promo_discount = $promo->discount;
                            }else{
                              $ticket->ticket_price = $request->price;
                            }
                            $ticket->event_date = $event->date;
                            $ticket->type = 'buy';
                            $ticket->row = $request->row;
                            $ticket->column = $request->column;
                            $ticket->found = $request->found;
                            $ticket->pay_type = $request->pay_type;

                            $output_file = 'img-' . time() . '.png';
                            $link = ('storage/qrcodes/'. $output_file);
                            $ticket->qr_code = $link;
                            $ticket->save();
                            $image = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                // ->generate('http://'.$_SERVER['SERVER_NAME'].'.kg/'.$ticket->id);
                                ->generate('ticket'.$ticket->id);
                            Storage::put('public/qrcodes/'. $output_file, $image);

                            $count = Count_buys::where('user_id',$ticket_user_id)->where('event_id',$event->id)->first();
                            if($count)
                            {
                                $count->count = $count->count + 1;
                                $count->save();
                            }
                            else
                            {
                                $count = new Count_buys();
                                $count->count = 1;
                                $count->user_id = $ticket_user_id;
                                $count->event_id = $event->id;
                                $count->save();
                            }
                            if(isset($promo))
                            {
                                $ticket->discount = 'Скидка по промокоду: '. $promo->name;
                            }
                            else
                            {

                            if (isset($event->rate[$ticket->ticket_format][4]) && $event->rate[$ticket->ticket_format][3] > Carbon::now())
                            {
                                $financial = Financial::where('event_id', $request->event_id)->first();
                                // $disc = $event->rate[$ticket->ticket_format][4] - $ticket->ticket_price;
                                $disc = $event->rate[$ticket->ticket_format][2] - $ticket->ticket_price;
                                $user = User::find($ticket_user_id);
                                $disc = ['user_id' => $user->id, 'discount' => $disc,'franch_id' => isset($user->franchise_id) ? $user->franchise_id : null];
                                $financial = $financial->add_discount($disc, $financial);
                                $ticket->discount = 'Скидка на тариф: '.$event->rate[$ticket->ticket_format][0];
                            }
                            }
                            $temp = collect($hall->column);
                            $collumn = $temp[$request->column];
                            $collumn = array_merge($collumn, ['ticket_id' => $ticket->id]);
                            if($request->show == 'true' || $request->show == 'on'){
                            $collumn = array_merge($collumn, ['show' => 1]);
                            }
                            $temp[$request->column] = $collumn;
                            $hall->column = collect($temp);
                            $hall->save();

                            /////////////////////////////////////////////////////////////////////////////////////////////////////
                            $ticket_currency_id = $event->currency;
                            $ticket_currency_name = CurrencyNames::find($ticket_currency_id)->currency;
                            /////////////////////////////////////////////////////////////////////////////////////////////////////


                            $financial = Financial::where('event_id', $request->event_id)->first();

                            $income = [
                                'user_id' => $ticket_user_id,
                                'user_name' => User::find($ticket_user_id)->fullName(),
                                'sum' => $ticket->ticket_price,
                                'date' => Carbon::now(),
                                'discount' => isset($ticket->discount) ? 1 : 0,
                                "ticket_id"=>$ticket->id,
                                "ticket_currency_id"=>$ticket_currency_id,
                                "ticket_currency_name"=>$ticket_currency_name,
                            ];
                            $financial = $financial->add_income($income, $financial);


                            if (User::find($ticket_user_id)->role->id== 2 && isset(User::find($ticket_user_id)->franchise_id)) {
                                $franchise = User::find(User::find($ticket_user_id)->franchise_id);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                                $partners_role = $franchise->role_id;
                                $partners_percent = 0;
                                if($partners_role == 4 || $partners_role == 6){
                                    $partners_percent = $financial->franch_perc > 0 ? $financial->franch_perc : $franchise->percent;
                                }
                                elseif($partners_role == 5){
                                    $partners_percent = $financial->partner_perc > 0 ? $financial->partner_perc : $franchise->percent;
                                }
                                elseif($partners_role == 8){
                                    $partners_percent = $financial->samo_sales_perc > 0 ? $financial->samo_sales_perc : $franchise->percent;
                                }
                                else{
                                    $partners_percent = $franchise->percent;
                                }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                                $partners_sum = (($ticket->ticket_price / 100) * $partners_percent);
                                $partners_sum_full = $partners_sum;
                                $partners_franchise_sum = 0;//Доля франча партнера
                                $part_franch_percent = 0;//Доля франча партнера%
                                ///////////////////////////////////////////////////////////////////////////////////////////////////
                                if ($franchise->role->id== 5 && isset($franchise->franchise_id)) {
                                    $partners_franchise = User::find($franchise->franchise_id);
                                    $partners_franchise_sum = (($partners_sum / 100) * $partners_franchise->percent_from_partner);
                                    $part_franch_percent = $partners_franchise->percent_from_partner;
                                    $partners_franchise_percent = [
                                        'partners_franchise_id' => $partners_franchise->id,
                                        'partners_franchise_name'=>$partners_franchise->fullName(),
                                        'partners_franchise_status'=>$partners_franchise->role->display_name,
                                        'partners_franchise_sum' => $partners_franchise_sum,
                                        "part_franch_percent"=>$part_franch_percent,
                                        'date' => Carbon::now(),
                                        'partner_id' => $franchise->id,
                                        'client_id' => $ticket_user_id,
                                        "ticket_currency_id"=>$ticket_currency_id,
                                        "ticket_currency_name"=>$ticket_currency_name,
                                    ];
                                    $financial->add_franchise_percent_from_partner($partners_franchise_percent, $financial);
                                    $partners_sum = $partners_sum-$partners_franchise_sum;
                                    }
                                ///////////////////////////////////////////////////////////////////////////////////////////////////
                                $franchise_percent = [
                                    'user_id' => User::find($ticket_user_id)->franchise_id,
                                    'franch_name'=>$franchise->fullName(),
                                    'franch_status'=>$franchise->role->display_name,
                                    'sum' => $partners_sum,
                                    'full_sum' => $partners_sum_full,
                                    "franch_percent"=>$partners_percent,
                                    'date' => Carbon::now(),
                                    'client_id' => $ticket_user_id,
                                    'partners_franchise_sum' => $partners_franchise_sum,
                                    "part_franch_percent"=>$part_franch_percent,
                                    "ticket_currency_id"=>$ticket_currency_id,
                                    "ticket_currency_name"=>$ticket_currency_name,
                                ];
                                $financial = $financial->add_franchise_percent($franchise_percent, $financial);
                                $EventFranchConnect = EventFranchFind::where('event_id', $request->event_id)->where('franchise_id', $franchise->id)->first();
                                if (!$EventFranchConnect) {
                                    $EventFranchConnect = new EventFranchFind();
                                    $EventFranchConnect->event_id = $request->event_id;
                                    $EventFranchConnect->franchise_id = $franchise->id;
                                    $EventFranchConnect->save();
                                }

                            }

                            $ticketDesign = TicketDesign::where('event_id', $event->id)->first();

                            $user = User::find($ticket_user_id);

                            // return View('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $link, 'user' => $user]);

                            $pdf = PDF::loadView('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $ticket->qr_code, 'user' => $user]);
                             // Storage::put('public/ticketpdf/'.$event->title.'-'.$user->last_name.'_'.$user->name.'_'.$user->middle_name.'.pdf', $pdf->output());
                            Storage::put('public/ticketpdf/' . $event->title . '-' . $user->fullName() . '.pdf', $pdf->output());

                            $data = array(
                                'name' => $user->fullName(),
                                'email' => $user->email,
                                'ticket' => $_SERVER['SERVER_NAME'].'/ticket_show/'.$ticket->id,
                            );

                            Mail::to($data['email'])->send(new Pdfsend($data));

                            return response()->json([
                                    'status' => "success",
                                    'check' => 0,
                                ], 200);
                            }//hall row check end
                    }//hall loop
                }
                else if($event->scheme == 0) {
                    if ($request->ticket != null) {
                        $ticket = Ticket::find($request->ticket);
                    } else {
                        $ticket = new Ticket();
                    }
                    $ticket->user_id = $ticket_user_id;
                    $ticket->event_id = $request->event_id;
                    $ticket->ticket_format = 1;
                    $promo = PromoCode::where('promo', $request->promo)->first();
                    if ($promo) {
                        $ticket->ticket_price = $request->promotion - (($request->promotion / 100) * $promo->discount);
                        $ticket->discount = (($request->promotion / 100) * $promo->discount);
                        $ticket->promo_id = $promo->id;
                        $ticket->promo_name = $promo->name;
                        $ticket->promo_discount = $promo->discount;
                    } else {
                        $ticket->ticket_price = $request->price;
                    }
                    $ticket->event_date = $event->date;
                    $ticket->type = 'buy';
                    $ticket->found = $request->found;
                    $ticket->pay_type = $request->pay_type;

                    $output_file = 'img-' . time() . '.png';

                    $link = ('storage/qrcodes/' . $output_file);
                    $ticket->qr_code = $link;
                    $ticket->save();
                    $image = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate('ticket' . $ticket->id);
                    Storage::put('public/qrcodes/' . $output_file, $image);


                    $count = Count_buys::where('user_id', $ticket_user_id)->where('event_id', $event->id)->first();
                    if ($count) {
                        $count->count = $count->count + 1;
                        $count->save();
                    } else {
                        $count = new Count_buys();
                        $count->count = 1;
                        $count->user_id = $ticket_user_id;
                        $count->event_id = $event->id;
                        $count->save();
                    }
                    if (isset($promo)) {
                        $ticket->discount = 'Скидка по промокоду: ' . $promo->name;
                    } else {
                        if (isset($event->rate[$ticket->ticket_format][4]) && $event->rate[$ticket->ticket_format][3] > Carbon::now()) {
                            $financial = Financial::where('event_id', $request->event_id)->first();
                            $disc = $event->rate[$ticket->ticket_format][4] - $ticket->ticket_price;
                            $user = User::find($ticket_user_id);
                            $disc = ['user_id' => $user->id, 'discount' => $disc, 'franch_id' => isset($user->franchise_id) ? $user->franchise_id : null];
                            $financial = $financial->add_discount($disc, $financial);
                            $ticket->discount = 'Скидка на тариф: ' . $event->rate[$ticket->ticket_format][0];
                        }
                    }


                    /////////////////////////////////////////////////////////////////////////////////////////////////////
                    $ticket_currency_id = $event->currency;
                    $ticket_currency_name = CurrencyNames::find($ticket_currency_id)->currency;
                    /////////////////////////////////////////////////////////////////////////////////////////////////////


                    $financial = Financial::where('event_id', $request->event_id)->first();

                    $income = [
                        'user_id' => $ticket_user_id,
                        'user_name' => User::find($ticket_user_id)->fullName(),
                        'sum' => $ticket->ticket_price,
                        'date' => Carbon::now(),
                        'discount' => isset($ticket->discount) ? 1 : 0,
                        "ticket_id"=>$ticket->id,
                        "ticket_currency_id"=>$ticket_currency_id,
                        "ticket_currency_name"=>$ticket_currency_name,
                    ];
                    $financial = $financial->add_income($income, $financial);





                    if (User::find($ticket_user_id)->role->id== 2 && isset(User::find($ticket_user_id)->franchise_id)) {
                        $franchise = User::find(User::find($ticket_user_id)->franchise_id);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        $partners_role = $franchise->role_id;
                        $partners_percent = 0;
                        if($partners_role == 4 || $partners_role == 6){
                            $partners_percent = $financial->franch_perc > 0 ? $financial->franch_perc : $franchise->percent;
                        }
                        elseif($partners_role == 5){
                            $partners_percent = $financial->partner_perc > 0 ? $financial->partner_perc : $franchise->percent;
                        }
                        elseif($partners_role == 8){
                            $partners_percent = $financial->samo_sales_perc > 0 ? $financial->samo_sales_perc : $franchise->percent;
                        }
                        else{
                            $partners_percent = $franchise->percent;
                        }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        $partners_sum = (($ticket->ticket_price / 100) * $partners_percent);
                        $partners_sum_full = $partners_sum;
                        $partners_franchise_sum = 0;//Доля франча партнера
                        $part_franch_percent = 0;//Доля франча партнера%
                        ///////////////////////////////////////////////////////////////////////////////////////////////////
                        if ($franchise->role->id== 5 && isset($franchise->franchise_id)) {
                            $partners_franchise = User::find($franchise->franchise_id);
                            $partners_franchise_sum = (($partners_sum / 100) * $partners_franchise->percent_from_partner);
                            $part_franch_percent = $partners_franchise->percent_from_partner;
                            $partners_franchise_percent = [
                                'partners_franchise_id' => $partners_franchise->id,
                                'partners_franchise_name'=>$partners_franchise->fullName(),
                                'partners_franchise_status'=>$partners_franchise->role->display_name,
                                'partners_franchise_sum' => $partners_franchise_sum,
                                "part_franch_percent"=>$part_franch_percent,
                                'date' => Carbon::now(),
                                'partner_id' => $franchise->id,
                                'client_id' => $ticket_user_id,
                                "ticket_currency_id"=>$ticket_currency_id,
                                "ticket_currency_name"=>$ticket_currency_name,
                            ];
                            $financial->add_franchise_percent_from_partner($partners_franchise_percent, $financial);
                            $partners_sum = $partners_sum-$partners_franchise_sum;
                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////////////
                        $franchise_percent = [
                            'user_id' => User::find($ticket_user_id)->franchise_id,
                            'franch_name'=>$franchise->fullName(),
                            'franch_status'=>$franchise->role->display_name,
                            'sum' => $partners_sum,
                            'full_sum' => $partners_sum_full,
                            "franch_percent"=>$partners_percent,
                            'date' => Carbon::now(),
                            'client_id' => $ticket_user_id,
                            'partners_franchise_sum' => $partners_franchise_sum,
                            "part_franch_percent"=>$part_franch_percent,
                            "ticket_currency_id"=>$ticket_currency_id,
                            "ticket_currency_name"=>$ticket_currency_name,
                        ];
                        $financial = $financial->add_franchise_percent($franchise_percent, $financial);
                        $EventFranchConnect = EventFranchFind::where('event_id', $request->event_id)->where('franchise_id', $franchise->id)->first();
                        if (!$EventFranchConnect) {
                            $EventFranchConnect = new EventFranchFind();
                            $EventFranchConnect->event_id = $request->event_id;
                            $EventFranchConnect->franchise_id = $franchise->id;
                            $EventFranchConnect->save();
                        }





                    }




                    $ticketDesign = TicketDesign::where('event_id', $event->id)->first();
                    $user = User::find($ticket_user_id);

                    $pdf = PDF::loadView('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $link, 'user' => $user]);
                    Storage::put('public/ticketpdf/' . $event->title . '-' . $user->fullName() . '.pdf', $pdf->output());
                    // Storage::put('public/ticketpdf/'.$event->title.'-'.$user->last_name.'_'.$user->name.'_'.$user->middle_name.'.pdf', $pdf->output());
                    $data = array(
                        'name' => $user->fullName(),
                        'email' => $user->email,
                        'ticket' => $_SERVER['SERVER_NAME'].'/ticket_show/'.$ticket->id,
                    );
                    Mail::to($data['email'])->send(new Pdfsend($data));

                    return response()->json([
                        'status' => "success",
                        'check' => 0,
                    ], 200);

                }
            }// closing else on reserve check
            }// closing else on check buy
    }

    public function reserve_ticket(Request $request)
    {
        if(Auth::user()->role_id == 3 || Auth::user()->role_id == 6 && $request->who=="admin" ){
            $ticket_user_id = $request->user_id;
        }
        elseif(Auth::user()->role_id == 2){
            $ticket_user_id = Auth::id();
        }
        else{
            return response()->json([
                'status' => "Cant buy",
            ], 200);
        }
        $check_buy = Ticket::where('user_id', $ticket_user_id)->where('event_id',$request->event_id)->where('type','buy')->get()->first();

        if(isset($check_buy))
        {
            if ($request->ajax()){
                return response()->json([
                    'status' => "error",
                    'check' => 0,

                ], 200);
            }
        }
        else
        {
            $check_reserve = Ticket::where('user_id', $ticket_user_id)->where('event_id', $request->event_id)->where('type', 'reserve')->get()->first();
            if (isset($check_reserve))
            {
                if ($request->ajax()){
                    return response()->json([
                        'status' => "error",
                        'check' => 1,
                    ], 200);
                }
            }
            else {
                $event = Event::find($request->event_id);
                if($event->scheme == 1) {
                    $halls = Hall::where('event_id', $request->event_id)->get();

                    foreach ($halls as $hall) {
                        if ($hall->row == $request->row) {
                            $ticket = new Ticket();
                            $ticket->user_id = $ticket_user_id;
                            $ticket->event_id = $request->event_id;
                            $ticket->ticket_format = $hall->column[$request->column]['status'];
                            if($request->promo){
                              $promo = PromoCode::where('promo',$request->promo)->first();
                              $ticket->ticket_price = $event->rate[$ticket->ticket_format][2] - (($event->rate[$ticket->ticket_format][2]/100)*$promo->discount);
                              $ticket->discount = (($event->rate[$ticket->ticket_format][2] / 100) * $promo->discount);
                              $ticket->promo_id = $promo->id;
                              $ticket->promo_name = $promo->name;
                              $ticket->promo_discount = $promo->discount;
                            }else{
                              $ticket->ticket_price = $request->price;
                            }
                            $ticket->event_date = $event->date;
                            $ticket->reserve_date = Carbon::parseFromLocale($event->reserve_date, 'ru') > Carbon::now()->subDays(-7) ? Carbon::now()->subDays(-7) : Carbon::parseFromLocale($event->reserve_date, 'ru');
                            $ticket->type = 'reserve';
                            $ticket->row = $request->row;
                            $ticket->column = $request->column;
                            $ticket->found = $request->found;
                            $ticket->qr_code = 'none';
                            $ticket->price_in_rub = $ticket->ticket_price * $event->convert_rub;
                            $ticket->save();
                            $count = Count_buys::where('user_id',$ticket_user_id)->where('event_id',$event->id)->first();
                            if($count)
                            {
                                $count->count = $count->count + 1;
                                $count->save();
                            }
                            else
                            {
                                $count = new Count_buys();
                                $count->count = 1;
                                $count->user_id = $ticket_user_id;
                                $count->event_id = $event->id;
                                $count->save();
                            }

                            $temp = collect($hall->column);
                            $collumn = $temp[$request->column];
                            $collumn = array_merge($collumn, ['reserve_id' => $ticket->id]);
                            if($request->show == 'true' || $request->show == 'on'){
                                $collumn = array_merge($collumn, ['show' => 1]);
                            }
                            $temp[$request->column] = $collumn;
                            $hall->column = collect($temp);
                            $hall->save();
                        }
                    }
                      $for_user = User::find($ticket_user_id);
                      if($for_user){
                        Mail::to($for_user->email)->send(new SendNotificationAboutReserve($event,$for_user));
                      }
                }
                else if ($event->scheme == 0)
                {
                    $ticket = new Ticket();
                    $ticket->user_id = $ticket_user_id;
                    $ticket->event_id = $request->event_id;
                    $ticket->ticket_format = $request->format_id;
                    if($request->promo){
                      $promo = PromoCode::where('promo',$request->promo)->first();
                      $ticket->ticket_price = $event->rate[$ticket->ticket_format][2] - (($event->rate[$ticket->ticket_format][2]/100)*$promo->discount);
                      $ticket->discount = (($event->rate[$ticket->ticket_format][2] / 100) * $promo->discount);
                      $ticket->promo_id = $promo->id;
                      $ticket->promo_name = $promo->name;
                      $ticket->promo_discount = $promo->discount;
                    }else{
                      $ticket->ticket_price = $request->price;
                    }
                    $ticket->event_date = $event->date;
                    $ticket->type = 'reserve';
                    $ticket->found = $request->found;
                    $ticket->qr_code = 'none';
                    $ticket->price_in_rub = $ticket->ticket_price * $event->convert_rub;
                    $ticket->save();
                    $count = Count_buys::where('user_id',$ticket_user_id)->where('event_id',$event->id)->first();
                    if($count)
                    {
                        $count->count = $count->count + 1;
                        $count->save();
                    }
                    else
                    {
                        $count = new Count_buys();
                        $count->count = 1;
                        $count->user_id = $ticket_user_id;
                        $count->event_id = $event->id;
                        $count->save();
                    }
                    $for_user = User::find($ticket_user_id);
                    if($for_user){
                      Mail::to($for_user->email)->send(new SendNotificationAboutReserve($event,$for_user));
                    }
                }

                if ($request->ajax()) {
                    return response()->json([
                        'status' => "success",
                        'check' => 0,
                    ], 200);
                }
            }
        }
    }
    public function reserve_ticket_for_payment(Request $request)
    {
        if(Auth::user()->role_id == 3 || Auth::user()->role_id == 6 && $request['who']=="admin" ){
            $ticket_user_id = $request['user_id'];
        }
        elseif(Auth::user()->role_id == 2){
            $ticket_user_id = Auth::id();
        }
        else{
            session(['check' => '88']);
            return redirect()->route('main');
        }
        $buyed_msg = '0';
        $reserved_msg = '1';

        if(auth()->user()->role_id==3 or auth()->user()->role_id==6){
          $buyed_msg = '0_admin';
          $reserved_msg = '1_admin';
        }
        $check_buy = Ticket::where('user_id', $ticket_user_id)->where('event_id',$request['event_id'])->where('type','buy')->get()->first();
        if(isset($check_buy))
        {
              session(['check' => $buyed_msg]);
              return redirect()->route('main');
        }
        else{
          $check_reserve = Ticket::where('user_id', $ticket_user_id)->where('event_id', $request['event_id'])->where('type', 'reserve')->get()->first();

            if (isset($check_reserve)  && $request['ticket'] == null)
            {
              session(['check' => $reserved_msg]);
              return redirect()->route('main');
            }
            else {
                $event = Event::find($request['event_id']);
                if($event->scheme == 1) {
                $halls = Hall::where('event_id', $request['event_id'])->get();

                foreach ($halls as $hall) {
                    if ($hall->row == $request['row']) {
                        if ($request['ticket'] != null)
                        {
                            $ticket = Ticket::find($request['ticket']);
                        }
                        else
                        {
                            $ticket = new Ticket();
                        }
                        $ticket->user_id = $ticket_user_id;
                        $ticket->client_name = User::find($ticket_user_id)->fullName();

                        if (User::find($ticket_user_id)->role->id== 2 && isset(User::find($ticket_user_id)->franchise_id)) {
                            $seller = User::find(User::find($ticket_user_id)->franchise_id);
                            $ticket->seller_name = $seller->fullName();
                            $ticket->seller_id = $seller->id;
                            $ticket->seller_role_id = $seller->role_id;
                        }
                        $ticket->event_id = $request['event_id'];
                        $ticket->event_admin_id= $event->user_id;
                        $ticket->ticket_format = $hall->column[$request['column']]['status'];
                        if($request['promo']){
                          $promo = PromoCode::where('promo',$request['promo'])->first();
                          $ticket->ticket_price = $event->rate[$ticket->ticket_format][2] - (($event->rate[$ticket->ticket_format][2]/100)*$promo->discount);
                          $ticket->discount = (($event->rate[$ticket->ticket_format][2] / 100) * $promo->discount);
                          $ticket->promo_id = $promo->id;
                          $ticket->promo_name = $promo->name;
                          $ticket->promo_discount = $promo->discount;
                        }else{
                          $ticket->ticket_price = $request['price'];
                        }
                        $ticket->event_date = $event->date;
                        $ticket->reserve_date = Carbon::parseFromLocale($event->reserve_date, 'ru') > Carbon::now()->subDays(-7) ? Carbon::now()->subDays(-7) : Carbon::parseFromLocale($event->reserve_date, 'ru');
                        $ticket->type = 'reserve';
                        $ticket->process = 'pending';
                        $ticket->row = $request['row'];
                        $ticket->column = $request['column'];
                        $ticket->found = $request['found'];
                        $ticket->pay_type = $request['pay_type'];
                        $ticket->qr_code = 'none';
                        $count = Count_buys::where('user_id',$ticket_user_id)->where('event_id',$event->id)->first();
                        if($count)
                        {
                            $count->count = $count->count + 1;
                            $count->save();
                        }
                        else
                        {
                            $count = new Count_buys();
                            $count->count = 1;
                            $count->user_id = $ticket_user_id;
                            $count->event_id = $event->id;
                            $count->save();
                        }
                        $ticket->save();
                        $temp = collect($hall->column);
                        $collumn = $temp[$request['column']];
                        $collumn = array_merge($collumn, ['reserve_id' => $ticket->id]);
                        if($request['show'] == 'true' || $request['show'] == 'on'){
                            $collumn = array_merge($collumn, ['show' => 1]);
                        }
                        $temp[$request['column']] = $collumn;
                        $hall->column = collect($temp);
                        $hall->save();
                    }
                }

                }
                else if ($event->scheme == 0)
                  {

                    if ($request['ticket'] != null)
                    {
                        $ticket = Ticket::find($request['ticket']);
                    }
                    else
                    {
                        $ticket = new Ticket();
                    }
                    $ticket->user_id = $ticket_user_id;
                    $ticket->client_name = User::find($ticket_user_id)->fullName();

                    if (User::find($ticket_user_id)->role->id== 2 && isset(User::find($ticket_user_id)->franchise_id)) {
                        $seller = User::find(User::find($ticket_user_id)->franchise_id);
                        $ticket->seller_name = $seller->fullName();
                        $ticket->seller_id = $seller->id;
                        $ticket->seller_role_id = $seller->role_id;
                    }

                    $ticket->event_id = $request['event_id'];
                    $ticket->ticket_format =  $request['format_id'];
                    if($request['promo']){
                      $promo = PromoCode::where('promo',$request['promo'])->first();
                      $ticket->ticket_price = $event->rate[$ticket->ticket_format][2] - (($event->rate[$ticket->ticket_format][2]/100)*$promo->discount);
                      $ticket->discount = (($event->rate[$ticket->ticket_format][2] / 100) * $promo->discount);
                      $ticket->promo_id = $promo->id;
                      $ticket->promo_name = $promo->name;
                      $ticket->promo_discount = $promo->discount;
                    }else{
                      $ticket->ticket_price = $request['price'];
                    }
                    $ticket->event_date = $event->date;
                    $ticket->type = 'reserve';
                    $ticket->found = $request['found'];
                    $ticket->process = 'pending';
                    $ticket->pay_type = $request['pay_type'];
                    $ticket->qr_code = 'none';
                    $count = Count_buys::where('user_id',$ticket_user_id)->where('event_id',$event->id)->first();
                    if($count){
                        $count->count = $count->count + 1;
                        $count->save();
                    }
                    else
                    {
                        $count = new Count_buys();
                        $count->count = 1;
                        $count->user_id = $ticket_user_id;
                        $count->event_id = $event->id;
                        $count->save();
                    }

                }

                $ticket->price_in_rub = $ticket->ticket_price * $event->convert_rub;

                $client = new Client();
                $shopId = $event->yandex_shop_id;
                $secretKey = $event->yandex_secret_key;
                $payboxMerchantId = $event->paybox_merchant_id;
                $payboxSecretKey = $event->paybox_secret_key;
                $client->setAuth($shopId, $secretKey);
                $host = request()->getSchemeAndHttpHost();
                $currency = CurrencyNames::find($event->currency);
                $idempotence = uniqid('', true);
                $now = Carbon::now()->format('Y-m-d H:i');
                $expire_at = Carbon::now()->addHours(2)->format('Y-m-d H:i');
                $ticket->save();
                if($ticket->pay_type=="yandex"){
                    $payment = $client->createPayment(
                      array(
                          'amount' => array(
                              'value' => floatval($ticket->price_in_rub),
                              'currency' => 'RUB',
                          ),
                          'confirmation' => array(
                              'type' => 'redirect',
                              'return_url' => $host.'/check_certain_payment/'.$ticket->id,
                          ),
                          'expires_at'=>$expire_at,
                          'capture' => true,
                          'description' => substr($event->title,0,120),
                          'metadata' => array(
                                'created_at' => $now,
                            ),

                      ),
                      $idempotence,
                  );
                  $ticket->yandex_shop_id = $shopId;
                  $ticket->yandex_secret_key = $secretKey;
                  $ticket->idempotence = $idempotence;
                  $ticket->payment_id = $payment['_id'];
                  $ticket->save();
                  return redirect($payment['_confirmation']['_confirmationUrl']);
                }elseif($ticket->pay_type=="paybox"){
                  $requestForPayBox = [
                      'pg_merchant_id'=> $payboxMerchantId,
                      'pg_amount' => $ticket->ticket_price,
                      'pg_currency'=>$currency->currency,
                      'pg_salt' => Str::random(16),
                      'pg_order_id' => $idempotence,
                      'pg_description' => substr($event->title,0,120),
                      'pg_success_url'=>$host.'/paybox_success',
                      'pg_failure_url'=>$host.'/paybox_failure',
                      'pg_result_url'=>$host.'/paybox_result_payment',
                      'pg_language'=>'ru',
                      'pg_lifetime'=>7200,
                  ];

                  ksort($requestForPayBox);
                  array_unshift($requestForPayBox, 'payment.php');
                  array_push($requestForPayBox, $payboxSecretKey);

                  $requestForPayBox['pg_sig'] = md5(implode(';', $requestForPayBox));

                  unset($requestForPayBox[0], $requestForPayBox[1]);

                  $query = http_build_query($requestForPayBox);
                  $ticket->paybox_merchant_id = $payboxMerchantId;
                  $ticket->paybox_secret_key = $payboxSecretKey;
                  $ticket->idempotence = $idempotence;
                  $ticket->save();
                  return redirect('https://api.paybox.money/payment.php?'.$query);
                }else{
                    ////// ADMIN BUY -- Cash
                    session(['check' => 'succeed','event_id'=>$event->id]);
                    if ($event->scheme == 1) {
                        $hall = Hall::where('event_id', $event->id)->where('row', $ticket->row)->first();
                        if($hall){
                          $temp = collect($hall->column); //col
                          $column = $temp[$ticket->column];
                          unset($column['reserve_id']);
                          $column = array_merge($column, ['ticket_id' => $ticket->id]);
                          $temp[$ticket->column] = $column;
                          $hall->column = collect($temp);
                        }
                        $hall->save();
                    }
                    $ticket_user_id = $ticket->user_id;
                    $ticket->type = 'buy';
                    $ticket->process = 'succeeded';
                    $output_file = 'img-' . time() . '.png';
                    $link = ('storage/qrcodes/'. $output_file);
                    $ticket->qr_code = $link;
                    $ticket->save();
                    $image = QrCode::format('png')->size(200)->errorCorrection('H')->generate('ticket'.$ticket->id);
                    Storage::put('public/qrcodes/'. $output_file, $image);
                    $count = Count_buys::where('user_id',$ticket_user_id)->where('event_id',$event->id)->first();
                    if($count){
                        $count->count = $count->count + 1;
                        $count->save();
                    }else{
                        $count = new Count_buys();
                        $count->count = 1;
                        $count->user_id = $ticket_user_id;
                        $count->event_id = $event->id;
                        $count->save();
                    }
                    $ticket_currency_id = $event->currency;
                    $ticket_currency_name = CurrencyNames::find($ticket_currency_id)->currency;
                    $financial = $event->financial;
                    if ($event->rate[$ticket->ticket_format][2] > $ticket->ticket_price)
                    {
                        $disc = $event->rate[$ticket->ticket_format][2] - $ticket->ticket_price;
                        $user = User::find($ticket_user_id);
                        $disc = ['user_id' => $user->id, 'discount' => $disc,'franch_id' => isset($user->franchise_id) ? $user->franchise_id : null];
                        $financial = $financial->add_discount($disc, $financial);
                        // $ticket->discount = 'Скидка на тариф: '.$event->rate[$ticket->ticket_format][0];
                        $ticket->discount = $event->rate[$ticket->ticket_format][2] - $ticket->ticket_price;
                        $ticket->promo_name = $event->rate[$ticket->ticket_format][0];
                        $ticket->save();
                    }
                    $income = [
                        'user_id' => $ticket_user_id,
                        'user_name' => User::find($ticket_user_id)->fullName(),
                        'sum' => $ticket->ticket_price,
                        'date' => Carbon::now(),
                        'discount' => isset($ticket->discount) ? 1 : 0,
                        "ticket_id"=>$ticket->id,
                        "ticket_currency_id"=>$ticket_currency_id,
                        "ticket_currency_name"=>$ticket_currency_name,
                    ];
                    $financial->add_income($income, $financial);
                    if (User::find($ticket_user_id)->role->id== 2 && isset(User::find($ticket_user_id)->franchise_id)) {
                        $franchise = User::find(User::find($ticket_user_id)->franchise_id);
                        $partners_role = $franchise->role_id;
                        $partners_percent = 0;
                        if($partners_role == 4 || $partners_role == 6){
                            $partners_percent = $financial->franch_perc > 0 ? $financial->franch_perc : $franchise->percent;
                        }
                        elseif($partners_role == 5){
                            $partners_percent = $financial->partner_perc > 0 ? $financial->partner_perc : $franchise->percent;
                        }
                        elseif($partners_role == 8){
                            $partners_percent = $financial->samo_sales_perc > 0 ? $financial->samo_sales_perc : $franchise->percent;
                        }
                        else{
                            $partners_percent = $franchise->percent;
                        }
                        $partners_sum = (($ticket->ticket_price / 100) * $partners_percent);
                        $partners_sum_full = $partners_sum;
                        $partners_franchise_sum = 0;//Доля франча партнера
                        $part_franch_percent = 0;//Доля франча партнера%
                        if ($franchise->role->id== 5 && isset($franchise->franchise_id)) {
                            $partners_franchise = User::find($franchise->franchise_id);
                            $partners_franchise_sum = (($partners_sum / 100) * $partners_franchise->percent_from_partner);
                            $part_franch_percent = $partners_franchise->percent_from_partner;
                            $partners_franchise_percent = [
                                'partners_franchise_id' => $partners_franchise->id,
                                'partners_franchise_name'=>$partners_franchise->fullName(),
                                'partners_franchise_status'=>$partners_franchise->role_id,
                                'partners_franchise_sum' => $partners_franchise_sum,
                                "part_franch_percent"=>$part_franch_percent,
                                'date' => Carbon::now(),
                                'partner_id' => $franchise->id,
                                'client_id' => $ticket_user_id,
                                'client_name' => User::find($ticket_user_id)->fullName(),
                                "ticket_currency_id"=>$ticket_currency_id,
                                "ticket_currency_name"=>$ticket_currency_name,
                            ];
                            $financial->add_franchise_percent_from_partner($partners_franchise_percent, $financial);
                            $partners_sum = $partners_sum-$partners_franchise_sum;
                        }
                        $franchise_percent = [
                            'user_id' => User::find($ticket_user_id)->franchise_id,
                            'franch_name'=>$franchise->fullName(),
                            'franch_id'=>$franchise->id,
                            'franch_status'=>$franchise->role_id,
                            'sum' => $partners_sum,
                            'full_sum' => $partners_sum_full,
                            "franch_percent"=>$partners_percent,
                            'date' => Carbon::now(),
                            'client_id' => $ticket_user_id,
                            'client_name' => User::find($ticket_user_id)->fullName(),
                            'partners_franchise_sum' => $partners_franchise_sum,
                            "part_franch_percent"=>$part_franch_percent,
                            "ticket_currency_id"=>$ticket_currency_id,
                            "ticket_currency_name"=>$ticket_currency_name,
                        ];

                        // dd($franchise_percent);
                        $financial->add_franchise_percent($franchise_percent, $financial);
                        
                        $EventFranchConnect = EventFranchFind::where('event_id', $event->id)->where('franchise_id', $franchise->id)->first();
                        if (!$EventFranchConnect) {
                            $EventFranchConnect = new EventFranchFind();
                            $EventFranchConnect->event_id = $event->id;
                            $EventFranchConnect->franchise_id = $franchise->id;
                            $EventFranchConnect->save();
                        }
                    }
                    $ticketDesign = TicketDesign::where('event_id', $event->id)->first();
                    $user = User::find($ticket_user_id);
                    // $pdf = PDF::loadView('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $ticket->qr_code, 'user' => $user]);
                    // Storage::put('public/ticketpdf/' . $event->title . '-' . $user->fullName() . '.pdf', $pdf->output());
                    $data = array(
                        'name' => $user->fullName(),
                        'email' => $user->email,
                        'ticket' => $_SERVER['SERVER_NAME'].'/ticket_show/'.$ticket->id,
                    );
                    Mail::to($data['email'])->send(new Pdfsend($data));
                    session(['check' => 'succeded_admin']);
                    return redirect()->route('home');
                    ///////// END ADMIN BUY -- Cash
                }
            }
        }
    }
    public function admin_buy_reserved(Request $request,$id){
        $ticket = Ticket::find($id);
        $event = Event::find($ticket->event_id);
        $ticket_user = User::find($ticket->user_id);
        $event_currency = \App\CurrencyNames::find($event->currency)->currency;
        return view('event.buy.event_includes.admin_buy_reserved', ['ticket' => $ticket, 'event' => $event, 'ticket_user' => $ticket_user,'event_currency'=>$event_currency]);
    }
    public function view_ticket_full(Request $request,$id){
        $ticket = Ticket::find($id);
        $event = Event::find($ticket->event_id);
        $ticketDesign = TicketDesign::where('event_id', $event->id)->first();
        $link = $ticket->qr_code;
        $user = User::find($ticket->user_id);

        return view('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $link, 'user' => $user]);
    }

    public function checker(Request $request)
    {
        $row = explode(',', $request->row);
        $column = explode(',', $request->column);
        $price = explode(',', $request->price);
        $type = $request->type;

        return view('event.buy.multiple_buy',['row' => $row, 'column' => $column, 'price' => $price, 'event' => Event::find($request->event), 'type' => $type]);
    }

    public function multiple_buy(Request $request)
    {
        Session::forget('request');
        Session::put('request', $request->all());
//        dd($request->all());
//        return redirect()->back();

        $already = collect();
        $event = Event::find($request->event);
        $type = $request->type;

        for ($i = 0; $i < $request->count; $i ++)
        {
            $user = User::where('email',$request['email'.$i])->orWhere('contacts',$request['phone'.$i])->first();
            if($user)
            {
                $check_buy = Ticket::where('user_id', $user->id)->where('event_id',$event->id)->where('type','buy')->get()->first();
                if(isset($check_buy))
                {
                    $none = ['user' => $user, 'status' => 'buy'];
                    $already->push($none);
                }
                else
                {$check_reserve = Ticket::where('user_id', $user->id)->where('event_id', $event->id)->where('type', 'reserve')->get()->first();
                    if (isset($check_reserve))
                    {
                        $none = ['user' => $user, 'status' => 'reserve'];
                        $already->push($none);
                    }
                    else {
                        if($type = 'buy'){
                        if($event->scheme == 1) {
                            $halls = Hall::where('event_id', $request->event_id)->get();

                            foreach ($halls as $hall) {
                                if ($hall->row == $request->row) {
                                    $ticket = new Ticket();
                                    $ticket->user_id = Auth::id();
                                    $ticket->event_id = $request->event_id;
                                    $ticket->ticket_format = $hall->column[$request['column'.$i]]['status'];
                                    $ticket->ticket_price = $request['price'.$i];
                                    $ticket->event_date = $event->date;
                                    $ticket->type = 'buy';
//                                    $ticket->save();

                                    $temp = collect($hall->column);
                                    $collumn = $temp[$request->column];
                                    $collumn = array_merge($collumn, ['buy_id' => $ticket->id]);
                                    $temp[$request->column] = $collumn;
                                    $hall->column = collect($temp);
//                                    $hall->save();
                                }
                            }
                        }
                        else if ($event->scheme == 0)
                        {
                                $ticket = new Ticket();
                                $ticket->user_id = Auth::id();
                                $ticket->event_id = $request->event_id;
                                $ticket->ticket_format = 1;
                                $ticket->ticket_price = $request['price'.$i];
                                $ticket->event_date = $event->date;
                                $ticket->type = 'buy';
                               $ticket->save();
                        }
                        }
                        else if($type == 'reserve')
                        {
                            if($event->scheme == 1) {
                                $halls = Hall::where('event_id', $request->event_id)->get();

                                foreach ($halls as $hall) {
                                    if ($hall->row == $request->row) {
                                        $ticket = new Ticket();
                                        $ticket->user_id = Auth::id();
                                        $ticket->event_id = $request->event_id;
                                        $ticket->ticket_format = $hall->column[$request['column'.$i]]['status'];
                                        $ticket->ticket_price = $request['price'.$i];
                                        $ticket->event_date = $event->date;
                                        $ticket->type = 'reserve';
//                                        $ticket->save();

                                        $temp = collect($hall->column);
                                        $collumn = $temp[$request->column];
                                        $collumn = array_merge($collumn, ['reserve_id' => $ticket->id]);
                                        $temp[$request->column] = $collumn;
                                        $hall->column = collect($temp);
//                                        $hall->save();
                                    }
                                }
                            }
                            else if ($event->scheme == 0)
                            {
                                $ticket = new Ticket();
                                $ticket->user_id = Auth::id();
                                $ticket->event_id = $request->event_id;
                                $ticket->ticket_format = 1;
                                $ticket->ticket_price = $request['price'.$i];
                                $ticket->event_date = $event->date;
                                $ticket->type = 'reserve';
                               $ticket->save();
                            }
                        }
                    }
                }
//                dd(User::where('email',$request['email'.$i])->orWhere('contacts',$request['phone'.$i])->get());
            }
        }

    dd($already);
    }

    public function buy_for_client_events(Request $request){

        if(Auth::user()->role_id == 3)
        {
            $events = Event::all();
        }
        else if(Auth::user()->role_id == 6)
        {
            $user_id = Auth::user()->id;
            $events = Event::where('user_id',$user_id)->get();
        }

        return view('event.buy.events_list',['events' => $events]);
    }

    public function buy_for_client_one_event(Request $request,$id){
        $event = Event::find($id);
        $row = Hall::where('event_id', $id)->count();
        $column = Hall::where('event_id', $id)->first();
        $outrow = OutHall::where('event_id', $id)->count();
        $halls = Hall::where('event_id', $id)->get();
        $out_halls =OutHall::where('event_id', $id)->get();
        if ($column) {
            $collect = collect($column->column);
            $count = $collect->count();
            $out_width = OutHall::where('event_id', $id)->first()->width;
            $out_height = OutHall::where('event_id', $id)->first()->height;
        } else {
            $count = null;
            $out_width=null;
            $out_height = null;
        }
        return view('event.buy.event_includes.scene_admin', ['event' => $event, 'row' => $row, 'column' => $count, 'halls' => $halls, 'type' => 'buy', 'out_halls' => $out_halls, 'out_height' => $out_height, 'out_width' => $out_width, 'outrow' => $outrow]);
    }

    public function event_details_view(Request $request,$id){
        $event=Event::find($id);
        // $sold = Ticket::where('event_id',$id)->where('type','buy')->get();
        // $reserved = Ticket::where('event_id',$id)->where('type','reserve')->get();
        // $refunded = Ticket::where('event_id',$id)->where('type','return')->get();
        $all_tickets = Ticket::all()->where('event_id',$id);

        return view('event.buy.event_includes.ticket_list',['tickets' => $all_tickets]);
    }



     public function attendance_events(Request $request)
    {
        $ticket = Ticket::whereIn('type',['buy','done'])->pluck('event_id');
        $events_buy = Event::find($ticket);
        $data = [
            'events' => $events_buy
        ];
        return view('admin.events.attendance',['data' => $data]);
    }

     public function attendance_events_clients(Request $request,$id)
    {

        $event = Event::find($id);
        // $event_user_ids = Ticket::where('event_id',$id)->pluck('user_id');
        // $users = User::whereIn("id",$event_user_ids)->get();
        // $ticket_buy_event = Ticket::where('event_id',$id);

        // $joined_table = DB::table('tickets')->where("event_id",$id)->where("type","buy")->orWhere("type","done")->select(
        $joined_table = DB::table('tickets')->where('event_id',$id)->whereIn('type',['buy','done'])->select(
            'tickets.id as ticket_id',
            'tickets.type',
            'tickets.ticket_price',
            'tickets.event_id',
            'users.name',
            'users.last_name',
            'users.middle_name',
            'users.id'
        )->join('users','users.id','=','tickets.user_id')->get();
        // dd($joined_table);
        $data=[
            // 'users' => $users,
            'event' => $event,
            'joined_table'=>$joined_table
        ];
        return view('admin.events.attendance_users',['data' => $data]);
    }
     public function set_attendance_type(Request $request)
        {
            $ticket = Ticket::find($request->ticket_id);
            if ($ticket->type == "buy"){
                $ticket->type = "done";
            }
            elseif ($ticket->type == "done") {
                $ticket->type = "buy";
            }
            $ticket->save();
            // dd($ticket);
            return response()->json(['success' => 'Success']);
        }
    public function resend_ticket(Request $request){
        $event = Event::find($request->event_id);
        $user =  \Illuminate\Support\Facades\Auth::user();
        $data = array(
            'name' => $user->fullName(),
            'email' => $user->email,
            'ticket' => $_SERVER['SERVER_NAME'].'/ticket_show/'.$request->ticket_id,
        );
        Mail::to($data['email'])->send(new Pdfsend($data));
        return response()->json(['success' => 'Success']);
    }
    public function sort_event(Request $request){
      $ticket = Ticket::whereIn('type',['buy','done'])->pluck('event_id');
      $events = Event::find($ticket);
      $start = Carbon::createFromFormat('D M d Y H:i:s e+',$request->start)->format('Y-m-d');
      $end = Carbon::createFromFormat('D M d Y H:i:s e+',$request->end)->format('Y-m-d');
      foreach($events as $key=>$event){
        $date = Carbon::parseFromLocale($event->date,'ru')->format('Y-m-d');
        if($start <= $date and $end >= $date){
          continue;
        }else{
          $events->forget($key);
        }
      }
      return response()->json([
        'success' => 'success',
        'view'=>view('admin.events.include.attendance_for_events')->with('events',$events)->render(),
      ]);

    }
    public function sortByVistit(Request $request){
      $sort = ['buy','done'];
      if($request->val){
        $sort = [$request->val];
      }
      $joined_table = DB::table('tickets')->where('event_id',$request->id)->whereIn('type',$sort)->select(
          'tickets.id as ticket_id',
          'tickets.type',
          'tickets.ticket_price',
          'tickets.event_id',
          'users.name',
          'users.last_name',
          'users.middle_name',
          'users.id'
      )->join('users','users.id','=','tickets.user_id')->get();
      $data=[
          'joined_table'=>$joined_table
      ];
      return response()->json([
        'success' => 'success',
        'view'=>view('admin.events.include.attendance_users_table')->with('data',$data)->render(),
      ]);
    }
    public function check_certain_payment($id){
      $now = Carbon::now()->format('Y-m-d H:i');
      $ticket = Ticket::Find($id);
      $event = Event::find($ticket->event_id);
      $shopId = $ticket->yandex_shop_id;
      $secretKey = $ticket->yandex_secret_key;
      $paymentId = $ticket->payment_id;
      $idempotenceKey = $ticket->idempotence;
      $client = new Client();
      $client->setAuth($shopId, $secretKey);
      $payment = $client->getPaymentInfo($paymentId);
      if($payment['_status']=='canceled'){
        $history = new History();
        $history->type1('Отмена оплаты', $history, $event);
        $hall = Hall::where('event_id',$event->id)->where('row',$ticket->row)->first();
        if($hall){
          $temp = collect($hall->column);
          $column = $temp[$ticket->column];
          unset($column['reserve_id']);
          $temp[$ticket->column] = $column;
          $hall->column = $temp;
          $hall->save();
        }
        $ticket->delete();
        session(['check' => 'failed']);
      }else if($payment['_status']=='succeeded'){ ///&& $ticket->process != 'succeeded'
          session(['check' => 'succeed','event_id'=>$event->id]);
          if ($event->scheme == 1) {
              $hall = Hall::where('event_id', $event->id)->where('row', $ticket->row)->first();
              if($hall){
                $temp = collect($hall->column); //col
                $column = $temp[$ticket->column];
                unset($column['reserve_id']);
                $column = array_merge($column, ['ticket_id' => $ticket->id]);
                $temp[$ticket->column] = $column;
                $hall->column = collect($temp);
              }
              $hall->save();
          }
          $ticket_user_id = $ticket->user_id;
          $ticket->type = 'buy';
          $ticket->process = 'succeeded';
          $output_file = 'img-' . time() . '.png';
          $link = ('storage/qrcodes/'. $output_file);
          $ticket->qr_code = $link;
          $ticket->save();
          $image = QrCode::format('png')->size(200)->errorCorrection('H')->generate('ticket'.$ticket->id);
          Storage::put('public/qrcodes/'. $output_file, $image);
          $count = Count_buys::where('user_id',$ticket_user_id)->where('event_id',$event->id)->first();
          if($count){
              $count->count = $count->count + 1;
              $count->save();
          }else{
              $count = new Count_buys();
              $count->count = 1;
              $count->user_id = $ticket_user_id;
              $count->event_id = $event->id;
              $count->save();
          }
          $ticket_currency_id = $event->currency;
          $ticket_currency_name = CurrencyNames::find($ticket_currency_id)->currency;
          $financial = $event->financial;


/////////////////////////////////////////////////////////////////////////////////////////////////


            if ($event->rate[$ticket->ticket_format][2] > $ticket->ticket_price)
            {
                $disc = $event->rate[$ticket->ticket_format][2] - $ticket->ticket_price;
                $user = User::find($ticket_user_id);
                $disc = ['user_id' => $user->id, 'discount' => $disc,'franch_id' => isset($user->franchise_id) ? $user->franchise_id : null];
                $financial = $financial->add_discount($disc, $financial);
                // $ticket->discount = 'Скидка на тариф: '.$event->rate[$ticket->ticket_format][0];
                $ticket->discount = $event->rate[$ticket->ticket_format][2] - $ticket->ticket_price;
                $ticket->promo_name = $event->rate[$ticket->ticket_format][0];
                $ticket->save();
            }

//////////////////////////////////////////////////////////////////////////////////////////////////


          $income = [
              'user_id' => $ticket_user_id,
              'user_name' => User::find($ticket_user_id)->fullName(),
              'sum' => $ticket->ticket_price,
              'date' => Carbon::now(),
              'discount' => isset($ticket->discount) ? 1 : 0,
              "ticket_id"=>$ticket->id,
              "ticket_currency_id"=>$ticket_currency_id,
              "ticket_currency_name"=>$ticket_currency_name,
          ];
          $financial->add_income($income, $financial);


          if (User::find($ticket_user_id)->role->id== 2 && isset(User::find($ticket_user_id)->franchise_id)) {
              $franchise = User::find(User::find($ticket_user_id)->franchise_id);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              $partners_role = $franchise->role_id;
              $partners_percent = 0;
              if($partners_role == 4 || $partners_role == 6){
                  $partners_percent = $financial->franch_perc > 0 ? $financial->franch_perc : $franchise->percent;
              }
              elseif($partners_role == 5){
                  $partners_percent = $financial->partner_perc > 0 ? $financial->partner_perc : $franchise->percent;
              }
              elseif($partners_role == 8){
                  $partners_percent = $financial->samo_sales_perc > 0 ? $financial->samo_sales_perc : $franchise->percent;
              }
              else{
                  $partners_percent = $franchise->percent;
              }
              $partners_sum = (($ticket->ticket_price / 100) * $partners_percent);
              $partners_sum_full = $partners_sum;
              $partners_franchise_sum = 0;//Доля франча партнера
              $part_franch_percent = 0;//Доля франча партнера%
              if ($franchise->role->id== 5 && isset($franchise->franchise_id)) {
                  $partners_franchise = User::find($franchise->franchise_id);
                  $partners_franchise_sum = (($partners_sum / 100) * $partners_franchise->percent_from_partner);
                  $part_franch_percent = $partners_franchise->percent_from_partner;
                  $partners_franchise_percent = [
                      'partners_franchise_id' => $partners_franchise->id,
                      'partners_franchise_name'=>$partners_franchise->fullName(),
                      'partners_franchise_status'=>$partners_franchise->role_id,
                      'partners_franchise_sum' => $partners_franchise_sum,
                      "part_franch_percent"=>$part_franch_percent,
                      'date' => Carbon::now(),
                      'partner_id' => $franchise->id,
                      'client_id' => $ticket_user_id,
                      'client_name' => User::find($ticket_user_id)->fullName(),
                      "ticket_currency_id"=>$ticket_currency_id,
                      "ticket_currency_name"=>$ticket_currency_name,
                  ];
                  $financial->add_franchise_percent_from_partner($partners_franchise_percent, $financial);
                  $partners_sum = $partners_sum-$partners_franchise_sum;
              }
              $franchise_percent = [
                  'user_id' => User::find($ticket_user_id)->franchise_id,
                  'franch_name'=>$franchise->fullName(),
                  'franch_id'=>$franchise->id,
                  'franch_status'=>$franchise->role_id,
                  'sum' => $partners_sum,
                  'full_sum' => $partners_sum_full,
                  "franch_percent"=>$partners_percent,
                  'date' => Carbon::now(),
                  'client_id' => $ticket_user_id,
                  'client_name' => User::find($ticket_user_id)->fullName(),
                  'partners_franchise_sum' => $partners_franchise_sum,
                  "part_franch_percent"=>$part_franch_percent,
                  "ticket_currency_id"=>$ticket_currency_id,
                  "ticket_currency_name"=>$ticket_currency_name,
              ];
              $financial->add_franchise_percent($franchise_percent, $financial);
              $EventFranchConnect = EventFranchFind::where('event_id', $event->id)->where('franchise_id', $franchise->id)->first();
              if (!$EventFranchConnect) {
                  $EventFranchConnect = new EventFranchFind();
                  $EventFranchConnect->event_id = $event->id;
                  $EventFranchConnect->franchise_id = $franchise->id;
                  $EventFranchConnect->save();
              }
          }
          $ticketDesign = TicketDesign::where('event_id', $event->id)->first();
          $user = User::find($ticket_user_id);
        //   $pdf = PDF::loadView('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $ticket->qr_code, 'user' => $user]);
        //   Storage::put('public/ticketpdf/' . $event->title . '-' . $user->fullName() . '.pdf', $pdf->output());
          $data = array(
              'name' => $user->fullName(),
              'email' => $user->email,
              'ticket' => $_SERVER['SERVER_NAME'].'/ticket_show/'.$ticket->id,
          );
          AMOController::addDeal($user,$event,$ticket);
          Mail::to($data['email'])->send(new Pdfsend($data));
      }
      if(Auth::user()->role_id == 2){
        return redirect()->route('home');
      }
      else{
        return redirect('/admin.buy');
      }
    }
    public function check_payment(){
      $now = Carbon::now()->format('Y-m-d H:i');
      $events = Event::all();
      foreach($events as $event){
        $event_end = Carbon::parseFromLocale($event->end_date,'ru')->format('Y-m-d H:i');
        if($event_end>=$now){
          continue;
        }
        $client = new Client();
        if($event->yandex_shop_id and $event->yandex_secret_key){
          $shopId = $event->yandex_shop_id;
          $secretKey = $event->yandex_secret_key;
          $client->setAuth($shopId, $secretKey);
          $tickets = Ticket::where('event_id',$event->id)->where('process','pending')->get();
          foreach($tickets as $ticket){
            if($ticket->payment_id and $ticket->idempotence){
              $paymentId = $ticket->payment_id;
              $idempotenceKey = $ticket->idempotence;
              $payment = $client->getPaymentInfo($paymentId);

              $payment_time = Carbon::make($payment['_metadata']['created_at'])->addHours(2)->format('Y-m-d h:i');
              if($payment['_status']=='pending'){
                if($payment_time <= $now){

                  $history = new History();
                  $history->type1('Время ожидания истекло', $history, $event);
                  $hall = Hall::where('event_id',$event->id)->where('row',$ticket->row)->first();
                  $temp = collect($hall->column);
                  $column = $temp[$ticket->column];
                  unset($column['reserve_id']);
                  $temp[$ticket->column] = $column;
                  $hall->column = $temp;
                  $hall->save();
                  $ticket->delete();

                }

              }else if($payment['_status']=='canceled'){

                $history = new History();
                $history->type1('Отмена оплаты', $history, $event);
                $hall = Hall::where('event_id',$event->id)->where('row',$ticket->row)->first();
                if($hall){
                  $temp = collect($hall->column);
                  $column = $temp[$ticket->column];
                  unset($column['reserve_id']);
                  $temp[$ticket->column] = $column;
                  $hall->column = $temp;
                  $hall->save();
                }
                $ticket->delete();

              }else if($payment['_status']=='succeeded'){

                  if ($event->scheme == 1) {
                      $hall = Hall::where('event_id', $event->id)->where('row', $ticket->row)->first();
                      if($hall){
                        $temp = collect($hall->column); //col
                        $column = $temp[$ticket->column];
                        unset($column['reserve_id']);
                        $column = array_merge($column, ['ticket_id' => $ticket->id]);
                        $temp[$ticket->column] = $column;
                        $hall->column = collect($temp);
                        $hall->save();
                      }
                  }
                  $ticket_user_id = $ticket->user_id;
                  $ticket->type = 'buy';
                  $ticket->process = 'succeeded';
                  $output_file = 'img-' . time() . '.png';
                  $link = ('storage/qrcodes/'. $output_file);
                  $ticket->qr_code = $link;
                  $ticket->save();
                  $image = QrCode::format('png')->size(200)->errorCorrection('H')->generate('ticket'.$ticket->id);
                  Storage::put('public/qrcodes/'. $output_file, $image);
                  $count = Count_buys::where('user_id',$ticket_user_id)->where('event_id',$event->id)->first();
                  if($count){
                      $count->count = $count->count + 1;
                      $count->save();
                  }else{
                      $count = new Count_buys();
                      $count->count = 1;
                      $count->user_id = $ticket_user_id;
                      $count->event_id = $event->id;
                      $count->save();
                  }
                  $ticket_currency_id = $event->currency;
                  $ticket_currency_name = CurrencyNames::find($ticket_currency_id)->currency;
                  $financial = $event->financial;
                    $income = [
                        'user_id' => $ticket_user_id,
                        'user_name' => User::find($ticket_user_id)->fullName(),
                        'sum' => $ticket->ticket_price,
                        'date' => Carbon::now(),
                        'discount' => isset($ticket->discount) ? 1 : 0,
                        "ticket_id"=>$ticket->id,
                        "ticket_currency_id"=>$ticket_currency_id,
                        "ticket_currency_name"=>$ticket_currency_name,
                    ];
                  $financial->add_income($income, $financial);
                  if (User::find($ticket_user_id)->role->id== 2 && isset(User::find($ticket_user_id)->franchise_id)) {
                      $franchise = User::find(User::find($ticket_user_id)->franchise_id);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                      $partners_role = $franchise->role_id;
                      $partners_percent = 0;
                      if($partners_role == 4 || $partners_role == 6){
                          $partners_percent = $financial->franch_perc > 0 ? $financial->franch_perc : $franchise->percent;
                      }
                      elseif($partners_role == 5){
                          $partners_percent = $financial->partner_perc > 0 ? $financial->partner_perc : $franchise->percent;
                      }
                      elseif($partners_role == 8){
                          $partners_percent = $financial->samo_sales_perc > 0 ? $financial->samo_sales_perc : $franchise->percent;
                      }
                      else{
                          $partners_percent = $franchise->percent;
                      }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                      $partners_sum = (($ticket->ticket_price / 100) * $partners_percent);
                      $partners_sum_full = $partners_sum;
                      $partners_franchise_sum = 0;//Доля франча партнера
                      $part_franch_percent = 0;//Доля франча партнера%
                      ///////////////////////////////////////////////////////////////////////////////////////////////////
                      if ($franchise->role->id== 5 && isset($franchise->franchise_id)) {
                          $partners_franchise = User::find($franchise->franchise_id);
                          $partners_franchise_sum = (($partners_sum / 100) * $partners_franchise->percent_from_partner);
                          $part_franch_percent = $partners_franchise->percent_from_partner;
                          $partners_franchise_percent = [
                              'partners_franchise_id' => $partners_franchise->id,
                              'partners_franchise_name'=>$partners_franchise->fullName(),
                              'partners_franchise_status'=>$partners_franchise->role_d,
                              'partners_franchise_sum' => $partners_franchise_sum,
                              "part_franch_percent"=>$part_franch_percent,
                              'date' => Carbon::now(),
                              'partner_id' => $franchise->id,
                              'client_id' => $ticket_user_id,
                              "ticket_currency_id"=>$ticket_currency_id,
                              "ticket_currency_name"=>$ticket_currency_name,
                          ];
                          $financial->add_franchise_percent_from_partner($partners_franchise_percent, $financial);
                          $partners_sum = $partners_sum-$partners_franchise_sum;
                      }
                      ///////////////////////////////////////////////////////////////////////////////////////////////////
                      $franchise_percent = [
                          'user_id' => User::find($ticket_user_id)->franchise_id,
                          'franch_name'=>$franchise->fullName(),
                          'franch_status'=>$franchise->role_id,
                          'sum' => $partners_sum,
                          'full_sum' => $partners_sum_full,
                          "franch_percent"=>$partners_percent,
                          'date' => Carbon::now(),
                          'client_id' => $ticket_user_id,
                          'client_name' => User::find($ticket_user_id)->fullName(),
                          'partners_franchise_sum' => $partners_franchise_sum,
                          "part_franch_percent"=>$part_franch_percent,
                          "ticket_currency_id"=>$ticket_currency_id,
                          "ticket_currency_name"=>$ticket_currency_name,
                      ];
                      $financial->add_franchise_percent($franchise_percent, $financial);
                      $EventFranchConnect = EventFranchFind::where('event_id', $event->id)->where('franchise_id', $franchise->id)->first();
                      if (!$EventFranchConnect) {
                          $EventFranchConnect = new EventFranchFind();
                          $EventFranchConnect->event_id = $event->id;
                          $EventFranchConnect->franchise_id = $franchise->id;
                          $EventFranchConnect->save();
                      }
                  }
                  $ticketDesign = TicketDesign::where('event_id', $event->id)->first();
                  $user = User::find($ticket_user_id);
                //   $pdf = PDF::loadView('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $ticket->qr_code, 'user' => $user]);
                //   Storage::put('public/ticketpdf/' . $event->title . '-' . $user->fullName() . '.pdf', $pdf->output());
                    AMOController::addDeal($user,$event,$ticket);

                    $data = array(
                        'name' => $user->fullName(),
                        'email' => $user->email,
                        'ticket' => $_SERVER['SERVER_NAME'].'/ticket_show/'.$ticket->id,
                    );
                  Mail::to($data['email'])->send(new Pdfsend($data));

              }
            }
          }
        }
      }
    }

    public function paybox_result_payment(){
      $idempotence = $_GET['pg_order_id'];
      $result = $_GET['pg_result'];
      $payment = $_GET['pg_payment_id'];
      $now = Carbon::now()->format('Y-m-d H:i');
      $ticket = Ticket::where('idempotence',$idempotence)->where('process','pending')->first();
      $check_for_success = false;
      if($ticket){
        if($result == 0){
            $event = Event::find($ticket->event_id);
            $history = new History();
            $history->type1('Отмена оплаты', $history, $event);
            $hall = Hall::where('event_id',$event->id)->where('row',$ticket->row)->first();
            $temp = collect($hall->column);
            $column = $temp[$ticket->column];
            unset($column['reserve_id']);
            $temp[$ticket->column] = $column;
            $hall->column = $temp;
            $hall->save();
            $ticket->delete();
            session(['check' => 'failed']);
        }
        if($result == 1){
          $event = Event::find($ticket->event_id);
          session(['check' => 'succeed','event_id'=>$event->id]);
          if($event->scheme == 1){
              $hall = Hall::where('event_id', $event->id)->where('row', $ticket->row)->first();
              $temp = collect($hall->column); //col
              $column = $temp[$ticket->column];
              unset($column['reserve_id']);
              $column = array_merge($column, ['ticket_id' => $ticket->id]);
              $temp[$ticket->column] = $column;
              $hall->column = collect($temp);
              $hall->save();
          }
          $ticket_user_id = $ticket->user_id;
          $ticket->type = 'buy';
          $ticket->process = 'succeeded';
          $output_file = 'img-' . time() . '.png';
          $link = ('storage/qrcodes/'. $output_file);
          $ticket->qr_code = $link;
          $ticket->payment = $payment;
          $ticket->save();
          $image = QrCode::format('png')->size(200)->errorCorrection('H')->generate('ticket'.$ticket->id);
          Storage::put('public/qrcodes/'. $output_file, $image);
          $count = Count_buys::where('user_id',$ticket_user_id)->where('event_id',$event->id)->first();
          if($count){
              $count->count = $count->count + 1;
              $count->save();
          }else{
              $count = new Count_buys();
              $count->count = 1;
              $count->user_id = $ticket_user_id;
              $count->event_id = $event->id;
              $count->save();
          }
          $ticket_currency_id = $event->currency;
          $ticket_currency_name = CurrencyNames::find($ticket_currency_id)->currency;
          $financial = $event->financial;
        $income = [
            'user_id' => $ticket_user_id,
            'user_name' => User::find($ticket_user_id)->fullName(),
            'sum' => $ticket->ticket_price,
            'date' => Carbon::now(),
            'discount' => isset($ticket->discount) ? 1 : 0,
            "ticket_id"=>$ticket->id,
            "ticket_currency_id"=>$ticket_currency_id,
            "ticket_currency_name"=>$ticket_currency_name,
        ];
          $financial->add_income($income, $financial);
          if (User::find($ticket_user_id)->role->id== 2 && isset(User::find($ticket_user_id)->franchise_id)) {
              $franchise = User::find(User::find($ticket_user_id)->franchise_id);
              $partners_role = $franchise->role_id;
              $partners_percent = 0;
              if($partners_role == 4 || $partners_role == 6){
                  $partners_percent = $financial->franch_perc > 0 ? $financial->franch_perc : $franchise->percent;
              }
              elseif($partners_role == 5){
                  $partners_percent = $financial->partner_perc > 0 ? $financial->partner_perc : $franchise->percent;
              }
              elseif($partners_role == 8){
                  $partners_percent = $financial->samo_sales_perc > 0 ? $financial->samo_sales_perc : $franchise->percent;
              }
              else{
                  $partners_percent = $franchise->percent;
              }
              $partners_sum = (($ticket->ticket_price / 100) * $partners_percent);
              $partners_sum_full = $partners_sum;
              $partners_franchise_sum = 0;//Доля франча партнера
              $part_franch_percent = 0;//Доля франча партнера%
              if ($franchise->role->id== 5 && isset($franchise->franchise_id)) {
                  $partners_franchise = User::find($franchise->franchise_id);
                  $partners_franchise_sum = (($partners_sum / 100) * $partners_franchise->percent_from_partner);
                  $part_franch_percent = $partners_franchise->percent_from_partner;
                  $partners_franchise_percent = [
                      'partners_franchise_id' => $partners_franchise->id,
                      'partners_franchise_name'=>$partners_franchise->fullName(),
                      'partners_franchise_status'=>$partners_franchise->role_id,
                      'partners_franchise_sum' => $partners_franchise_sum,
                      "part_franch_percent"=>$part_franch_percent,
                      'date' => Carbon::now(),
                      'partner_id' => $franchise->id,
                      'client_id' => $ticket_user_id,
                      "ticket_currency_id"=>$ticket_currency_id,
                      "ticket_currency_name"=>$ticket_currency_name,
                  ];
                  $financial->add_franchise_percent_from_partner($partners_franchise_percent, $financial);
                  $partners_sum = $partners_sum-$partners_franchise_sum;
              }
              $franchise_percent = [
                  'user_id' => User::find($ticket_user_id)->franchise_id,
                  'franch_name'=>$franchise->fullName(),
                  'franch_status'=>$franchise->role_id,
                  'sum' => $partners_sum,
                  'full_sum' => $partners_sum_full,
                  "franch_percent"=>$partners_percent,
                  'date' => Carbon::now(),
                  'client_id' => $ticket_user_id,
                  'partners_franchise_sum' => $partners_franchise_sum,
                  "part_franch_percent"=>$part_franch_percent,
                  "ticket_currency_id"=>$ticket_currency_id,
                  "ticket_currency_name"=>$ticket_currency_name,
              ];
              $financial->add_franchise_percent($franchise_percent, $financial);
              $EventFranchConnect = EventFranchFind::where('event_id', $event->id)->where('franchise_id', $franchise->id)->first();
              if (!$EventFranchConnect) {
                  $EventFranchConnect = new EventFranchFind();
                  $EventFranchConnect->event_id = $event->id;
                  $EventFranchConnect->franchise_id = $franchise->id;
                  $EventFranchConnect->save();
              }
          }
          $ticketDesign = TicketDesign::where('event_id', $event->id)->first();
          $user = User::find($ticket_user_id);
        //   $pdf = PDF::loadView('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $ticket->qr_code, 'user' => $user]);
        //   Storage::put('public/ticketpdf/' . $event->title . '-' . $user->fullName() . '.pdf', $pdf->output());
          AMOController::addDeal($user,$event,$ticket);

          $data = array(
             'name' => $user->fullName(),
             'email' => $user->email,
             'ticket' => $_SERVER['SERVER_NAME'].'/ticket_show/'.$ticket->id,
          );
          Mail::to($data['email'])->send(new Pdfsend($data));
        }
      }
    }

    public function paybox_success(){
      $idempotence = $_GET['pg_order_id'];
      $payment = $_GET['pg_payment_id'];
      $now = Carbon::now()->format('Y-m-d H:i');
      $ticket = Ticket::where('idempotence',$idempotence)->first();
      $check_for_success = false;
      if($ticket){
        $event = Event::find($ticket->event_id);
        session(['check' => 'succeed','event_id'=>$event->id]);

        if($ticket->process != "succeeded"){

        if ($event->scheme == 1) {
            $hall = Hall::where('event_id', $event->id)->where('row', $ticket->row)->first();
            $temp = collect($hall->column); //col
            $column = $temp[$ticket->column];
            unset($column['reserve_id']);
            $column = array_merge($column, ['ticket_id' => $ticket->id]);
            $temp[$ticket->column] = $column;
            $hall->column = collect($temp);
            $hall->save();
        }
        $ticket_user_id = $ticket->user_id;
        $ticket->type = 'buy';
        $ticket->process = 'succeeded';
        $output_file = 'img-' . time() . '.png';
        $link = ('storage/qrcodes/'. $output_file);
        $ticket->qr_code = $link;
        $ticket->payment_id = $payment;
        $ticket->save();
        $image = QrCode::format('png')->size(200)->errorCorrection('H')->generate('ticket'.$ticket->id);
        Storage::put('public/qrcodes/'. $output_file, $image);
        $count = Count_buys::where('user_id',$ticket_user_id)->where('event_id',$event->id)->first();
        if($count){
            $count->count = $count->count + 1;
            $count->save();
        }else{
            $count = new Count_buys();
            $count->count = 1;
            $count->user_id = $ticket_user_id;
            $count->event_id = $event->id;
            $count->save();
        }
        $ticket_currency_id = $event->currency;
        $ticket_currency_name = CurrencyNames::find($ticket_currency_id)->currency;
        $financial = $event->financial;
        $income = [
            'user_id' => $ticket_user_id,
            'user_name' => User::find($ticket_user_id)->fullName(),
            'sum' => $ticket->ticket_price,
            'date' => Carbon::now(),
            'discount' => isset($ticket->discount) ? 1 : 0,
            "ticket_id"=>$ticket->id,
            "ticket_currency_id"=>$ticket_currency_id,
            "ticket_currency_name"=>$ticket_currency_name,
        ];
        $financial->add_income($income, $financial);
        if (User::find($ticket_user_id)->role->id== 2 && isset(User::find($ticket_user_id)->franchise_id)) {
            $franchise = User::find(User::find($ticket_user_id)->franchise_id);
            $partners_role = $franchise->role_id;
            $partners_percent = 0;
            if($partners_role == 4 || $partners_role == 6){
                $partners_percent = $financial->franch_perc > 0 ? $financial->franch_perc : $franchise->percent;
            }
            elseif($partners_role == 5){
                $partners_percent = $financial->partner_perc > 0 ? $financial->partner_perc : $franchise->percent;
            }
            elseif($partners_role == 8){
                $partners_percent = $financial->samo_sales_perc > 0 ? $financial->samo_sales_perc : $franchise->percent;
            }
            else{
                $partners_percent = $franchise->percent;
            }
            $partners_sum = (($ticket->ticket_price / 100) * $partners_percent);
            $partners_sum_full = $partners_sum;
            $partners_franchise_sum = 0;//Доля франча партнера
            $part_franch_percent = 0;//Доля франча партнера%
            if ($franchise->role->id== 5 && isset($franchise->franchise_id)) {
                $partners_franchise = User::find($franchise->franchise_id);
                $partners_franchise_sum = (($partners_sum / 100) * $partners_franchise->percent_from_partner);
                $part_franch_percent = $partners_franchise->percent_from_partner;
                $partners_franchise_percent = [
                    'partners_franchise_id' => $partners_franchise->id,
                    'partners_franchise_name'=>$partners_franchise->fullName(),
                    'partners_franchise_status'=>$partners_franchise->role_id,
                    'partners_franchise_sum' => $partners_franchise_sum,
                    "part_franch_percent"=>$part_franch_percent,
                    'date' => Carbon::now(),
                    'partner_id' => $franchise->id,
                    'client_id' => $ticket_user_id,
                    "ticket_currency_id"=>$ticket_currency_id,
                    "ticket_currency_name"=>$ticket_currency_name,
                ];
                $financial->add_franchise_percent_from_partner($partners_franchise_percent, $financial);
                $partners_sum = $partners_sum-$partners_franchise_sum;
            }
            $franchise_percent = [
                'user_id' => User::find($ticket_user_id)->franchise_id,
                'franch_name'=>$franchise->fullName(),
                'franch_status'=>$franchise->role_id,
                'sum' => $partners_sum,
                'full_sum' => $partners_sum_full,
                "franch_percent"=>$partners_percent,
                'date' => Carbon::now(),
                'client_id' => $ticket_user_id,
                'partners_franchise_sum' => $partners_franchise_sum,
                "part_franch_percent"=>$part_franch_percent,
                "ticket_currency_id"=>$ticket_currency_id,
                "ticket_currency_name"=>$ticket_currency_name,
            ];
            $financial->add_franchise_percent($franchise_percent, $financial);
            $EventFranchConnect = EventFranchFind::where('event_id', $event->id)->where('franchise_id', $franchise->id)->first();
            if (!$EventFranchConnect) {
                $EventFranchConnect = new EventFranchFind();
                $EventFranchConnect->event_id = $event->id;
                $EventFranchConnect->franchise_id = $franchise->id;
                $EventFranchConnect->save();
            }
        }
        $ticketDesign = TicketDesign::where('event_id', $event->id)->first();
        $user = User::find($ticket_user_id);
        // $pdf = PDF::loadView('admin.ticket.pdf', ['ticketDesign' => $ticketDesign, 'event' => $event, 'ticket' => $ticket, 'qr_code' => $ticket->qr_code, 'user' => $user]);
        // Storage::put('public/ticketpdf/' . $event->title . '-' . $user->fullName() . '.pdf', $pdf->output());
        AMOController::addDeal($user,$event,$ticket);
        $data = array(
            'name' => $user->fullName(),
            'email' => $user->email,
            'ticket' => $_SERVER['SERVER_NAME'].'/ticket_show/'.$ticket->id,
        );
          Mail::to($data['email'])->send(new Pdfsend($data));
        }
      }
      return redirect('/');
    }
    public function paybox_failure(){
      $idempotence = $_GET['pg_order_id'];
      $now = Carbon::now()->format('Y-m-d H:i');
      $ticket = Ticket::where('idempotence',$idempotence)->first();
      if($ticket){
        $event = Event::find($ticket->event_id);
        $history = new History();
        $history->type1('Отмена оплаты', $history, $event);
        $hall = Hall::where('event_id',$event->id)->where('row',$ticket->row)->first();
        $temp = collect($hall->column);
        $column = $temp[$ticket->column];
        unset($column['reserve_id']);
        $temp[$ticket->column] = $column;
        $hall->column = $temp;
        $hall->save();
        $ticket->delete();
        session(['check' => 'failed']);
      }
      return redirect('/');

    }
}
