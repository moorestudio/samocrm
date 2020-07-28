<?php

namespace App\Http\Controllers;

use App\Event;
use App\Financial;
use App\Hall;
use App\Ticket;
use App\Count_buys;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;
use YandexCheckout\Client;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function Ajax_SwitchReport(Request $request)
    {
        $event = Event::find($request->event);
        $event_currency = \App\CurrencyNames::find($event->currency)->currency;

        $financial = Financial::find($request->financial_id);
        if(isset($request->type))
        {
            if($request->type == 1)
            {
                $view = view('admin.reports.event_admin.event_includes.financial_income', [
                    'financial' => $financial,
                    'event_currency'=>$event_currency,
                ])->render();

            }
            elseif ($request->type == 2)
            {
                $view = view('admin.reports.event_admin.event_includes.financial_consuption', [
                    'financial' => $financial,
                    'event_currency'=>$event_currency,
                ])->render();

            }
            elseif ($request->type == 3)
            {
                $franch_per = $financial->franch_percent;
                $final = array();

                foreach ($franch_per as $result) {
                    if(!isset($final[$result['user_id']])) {
                        $final[$result['user_id']] = [$result['sum'],$result['franch_percent'],$result['partners_franchise_sum'],$result['part_franch_percent'],$result['franch_status'],$result['franch_name']];
                    } else {
                        $final[$result['user_id']][0] += $result['sum'];
                        $final[$result['user_id']][2] += $result['partners_franchise_sum'];
                    }
                }
                $view = view('admin.reports.event_admin.event_includes.financial_franchise', [
                    'financial' => $financial,
                    'event_currency'=>$event_currency,
                    'subtotals' =>$final,
                ])->render();

            }


            elseif ($request->type == 7)
            {
                $view = view('admin.reports.event_admin.event_includes.financial_raw_income', [
                    'financial' => $financial,
                    'event_currency'=>$event_currency,
                ])->render();

            }
            return response()->json([
                'view' => $view,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function Ajax_SwitchClient(Request $request)
    {
        $financial = Financial::find($request->financial_id);
        $event = Event::find($financial->event_id);
        $tickets = Ticket::where('event_id', $event->id)->get();

        if(isset($request->type))
        {
                $view = view('admin.reports.event_admin.event_includes.clients', [
                    'tickets' => $tickets,
                    'type' => $request->type,
                    'event' => $event,
                ])->render();

            return response()->json([
                'view' => $view,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function Ajax_addConsuption(Request $request)
    {
        $financial = Financial::find($request->financial);
        $consuption = [
            'title' => $request->name,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->fullName(),
            'sum' => $request->sum,
            'date' => Carbon::now()
        ];
        $financial = $financial->add_consuption($consuption,$financial);
        $event = Event::find($request->event);
        $event_currency = \App\CurrencyNames::find($event->currency)->currency;

        $total_raw_income = $financial->total_rawIncome;

        $view = view('admin.reports.event_admin.event_includes.money_report',[
            'financial' => $financial,
            'event_currency'=>$event_currency,
            'total_raw_income'=>$total_raw_income,
        ])->render();
        return response()->json([
            'view' => $view,
        ]);
    }


    public function Ajax_addIncome(Request $request)
    {
        $financial = Financial::find($request->financial);
        $event = Event::find($request->event);
        $user_id= Auth::user()->id;
        $raw_income = [
            'name'=>$request->name,
            'value'=>$request->sum,
            'user_id'=>$user_id,
            'user_name'=>Auth::user()->fullName(),
            'date' => Carbon::now()
        ];

        // dd($financial->raw_income[0]['name']);
        $check_existence = true;
        foreach (collect($financial->raw_income) as $raw_in) {
            if($request->name==$raw_in['name']){
                $check_existence = false;
            }
        }

        if($check_existence){
            $raw_income_collect = collect($raw_income);
            $temp_collect = collect($financial->raw_income);
            $temp_collect->push($raw_income_collect);
            $financial->raw_income = $temp_collect;
            $financial->total_rawIncome +=$raw_income['value'];
            $financial->total +=$raw_income['value'];
            $financial->save();



        }


        $total_raw_income = $financial->total_rawIncome;

        $event_currency = \App\CurrencyNames::find($event->currency)->currency;
        $view = view('admin.reports.event_admin.event_includes.money_report',[
            'financial' => $financial,
            'event_currency'=>$event_currency,
            'check_existence'=>$check_existence,
            'total_raw_income'=>$total_raw_income,
        ])->render();
        return response()->json([
            'view' => $view,
        ]);
    }

    public function Ajax_remove_raw_Income(Request $request)
    {
        $financial = Financial::find($request->financial);
        $event = Event::find($request->event);

        $check_existence = false;
        $pos_key = '';
        $inc_sum = '';
        foreach (collect($financial->raw_income) as $k=>$raw_in) {
            if($request->name==$raw_in['name']){
                $check_existence =  true;
                //есть
                $pos_key = $k;
                $inc_sum = $raw_in['value'];
            }
        }

        if($check_existence){

            $temp_collect = collect($financial->raw_income);

            unset($temp_collect[$pos_key]);
            $financial->raw_income = $temp_collect;
            $financial->total_rawIncome -=$inc_sum;
            $financial->total -=$inc_sum;
            $financial->save();

        }

        $total_raw_income = $financial->total_rawIncome;


        $event_currency = \App\CurrencyNames::find($event->currency)->currency;
        $view = view('admin.reports.event_admin.event_includes.money_report',[
            'financial' => $financial,
            'event_currency'=>$event_currency,
            'check_existence'=>$check_existence,
            'total_raw_income'=>$total_raw_income,
        ])->render();
        return response()->json([
            'view' => $view,
        ]);
    }
    public function Ajax_getRawIncome(Request $request)
    {
        $financial = Financial::find($request->financial);
        $event = Event::find($request->event);
        $name='';
        $sum = '';
        $check_existence = false;
        $pos_key = '';
        foreach (collect($financial->raw_income) as $k=>$raw_in) {
            if($request->name==$raw_in['name']){
                $check_existence =  true;
                //есть
                $pos_key = $k;

            }
        }
        if($check_existence){

            $temp_collect = collect($financial->raw_income);

            $found_raw=$temp_collect[$pos_key];
            $name = $found_raw['name'];
            $sum =  $found_raw['value'];
        }


        $event_currency = \App\CurrencyNames::find($event->currency)->currency;
        return response()->json([
            'name' => $name,
            'sum' => $sum,
            'event_currency'=>$event_currency,
        ]);
    }


    public function Ajax_raw_income_edit(Request $request)
    {
        $financial = Financial::find($request->financial);
        $event = Event::find($request->event);
        $user_id= Auth::user()->id;
        $raw_income = [
            'name'=>$request->name,
            'value'=>$request->sum,
            'user_id'=>$user_id,
            'user_name'=>Auth::user()->fullName(),
            'date' => Carbon::now()
        ];
        $check_existence = false;
        $inc_sum = '';
        $pos_key = '';
        foreach (collect($financial->raw_income) as $k=>$raw_in) {
            if($request->old_name==$raw_in['name']){
                $check_existence =  true;
                //есть
                $pos_key = $k;
                $inc_sum = $raw_in['value'];
            }
        }


        if($check_existence){
            $temp_collect = collect($financial->raw_income);
            unset($temp_collect[$pos_key]);
            $financial->total_rawIncome -=$inc_sum;
            $financial->total -=$inc_sum;
            //new
            $raw_income_collect = collect($raw_income);
            $temp_collect->push($raw_income_collect);
            //add
            $financial->raw_income = $temp_collect;
            $financial->total_rawIncome +=$raw_income['value'];
            $financial->total+=$raw_income['value'];
            $financial->save();
        }

        $total_raw_income = $financial->total_rawIncome;



        $event_currency = \App\CurrencyNames::find($event->currency)->currency;
        $view = view('admin.reports.event_admin.event_includes.money_report',[
            'financial' => $financial,
            'event_currency'=>$event_currency,
            'check_existence'=>$check_existence,
            'total_raw_income'=>$total_raw_income,
        ])->render();
        return response()->json([
            'view' => $view,
        ]);
    }

    public function Ajax_deleteConsuption(Request $request)
    {
        $financial = Financial::find($request->financial);
        $financial = $financial->delete_consuption($request->id, $financial);
        $event = Event::find($request->event);
        $event_currency = \App\CurrencyNames::find($event->currency)->currency;
        $total_raw_income = $financial->total_rawIncome;
        $view = view('admin.reports.event_admin.event_includes.money_report',[
            'financial' => $financial,
            'event_currency'=>$event_currency,
            'total_raw_income'=>$total_raw_income,
        ])->render();

        return response()->json([
           'view' => $view,
        ]);
    }

    public function Ajax_getConsuption(Request $request)
    {
        $financial = Financial::find($request->financial);
        $consuption = $financial->consuption[$request->id];
        $name = $consuption['title'];
        $sum = $consuption['sum'];

        return response()->json([
            'name' => $name,
            'sum' => $sum,
        ]);
    }

    public function Ajax_editConsuption(Request $request)
    {
        $financial = Financial::find($request->financial);
        $financial = $financial->edit_consuption($request->id, $financial, $request->name, $request->sum);
        $event = Event::find($request->event);
        $event_currency = \App\CurrencyNames::find($event->currency)->currency;
        $total_raw_income = $financial->total_rawIncome;
        $view = view('admin.reports.event_admin.event_includes.money_report',[
            'financial' => $financial,
            'event_currency'=>$event_currency,
            'total_raw_income'=>$total_raw_income,
        ])->render();

        return response()->json([
           'view' => $view,
        ]);
    }

    public function Ajax_changeParts(Request $request)
    {
        $financial = Financial::find($request->financial);
        $financial->samo_percent = $request->samo;
        $financial->event_percent = $request->event;
        $financial->speaker_percent = $request->speaker;

        $financial->save();
        ////////////////////////////////////////////////////////////////////////////
        $event = Event::find($request->event_id);
        $event_currency = \App\CurrencyNames::find($event->currency)->currency;
        ////////////////////////////////////////////////////////////////////////////
        $view = view('admin.reports.event_admin.event_includes.parts_report',[
            'financial' => $financial,
            'event' => $event,
            'event_currency' => $event_currency,
        ])->render();

        return response()->json([
           'view' => $view,
        ]);
    }

    public function Ajax_returnTicket(Request $request)
    {
        $ticket = Ticket::find($request->id);
        
        $financial = Financial::where('event_id',$ticket->event_id)->first();

        $financial->remove_income($ticket->user_id, $financial);
        if(isset($ticket->row) && isset($ticket->column)) {
            $hall = Hall::where('event_id',$ticket->event_id)->where('row',$ticket->row)->first();
            if($hall){
              $temp_ticket = collect($hall->column[$ticket->column]);
              $temp_hall = collect($hall->column);
              unset($temp_ticket['ticket_id']);
              unset($temp_ticket['reserve_id']);
              unset($temp_ticket['show']);
              $temp_hall[$ticket->column] = $temp_ticket;
              $hall->column = $temp_hall;
              $hall->save();
            }
        }
        $ticket->comment = $request->comment;
        $ticket->type = 'return';
        $ticket->save();
        if($ticket->pay_type=="yandex"){
          $shopId = $ticket->yandex_shop_id;
          $secretKey = $ticket->yandex_secret_key;
          $client = new Client();
          $client->setAuth($shopId, $secretKey);
          $client->createRefund(
              array(
                  'amount' => array(
                      'value' => $ticket->price_in_rub,
                      'currency' => 'RUB',
                  ),
                  'payment_id' => $ticket->payment_id,
              ),
              uniqid('', true)
          );
        }elseif($ticket->pay_type=="paybox"){
          $requestForPayBox = [
              'pg_merchant_id'=> $ticket->paybox_merchant_id,
              'pg_payment_id' => $ticket->payment_id,
              'pg_salt' => Str::random(16),
          ];
          ksort($requestForPayBox);
          array_unshift($requestForPayBox, 'revoke.php');
          array_push($requestForPayBox, $ticket->paybox_secret_key);

          $requestForPayBox['pg_sig'] = md5(implode(';', $requestForPayBox));

          unset($requestForPayBox[0], $requestForPayBox[1]);

          $query = http_build_query($requestForPayBox);
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL,'https://api.paybox.money/revoke.php');
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS,$query);
          $response_json = curl_exec($ch);
          curl_close($ch);
          $response = json_decode($response_json, true);
          dd($response_json,$ticket->paybox_secret_key,$ticket->paybox_merchant_id);
        }
        $count = Count_buys::where('user_id',$ticket->user_id)->where('event_id',$ticket->event_id)->first();
        
        if($count){
            $count->count = $count->count - 1;
            $count->save();
        }
        return response()->json([
            'status' => 'success',
        ]);
    }
}
