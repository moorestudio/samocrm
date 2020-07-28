<?php

namespace App\Http\Controllers;

use App\Count_buys;
use App\Event;
use App\EventFranchFind;
use App\Financial;
use App\Hall;
use App\History;
use App\Mail\contact_send;
use App\Mail\feedback_send;
use App\Mail\SendUserChangeFranch;
use App\Mail\SendUserConnectFranch;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Cookie;

class UserController extends Controller
{
    public function contact_send(Request $request){
        if(auth()->check()){
            if(auth()->user()->franchise_id){
                $user = User::find(auth()->user()->franchise_id);
            }else{
                $user = User::where('role_id',3)->first();
            }
        }else{
            $user = User::where('role_id',3)->first();
        }
        Mail::to($user)->send(new contact_send($request->name, $request->phone, $request->type, $request->email, $request->comment));
    }
    public function feedback_send(Request $request){
        if(auth()->check()){
            if(auth()->user()->franchise_id){
                $user = User::find(auth()->user()->franchise_id);
            }else{
                $user = User::where('role_id',3)->first();
            }
        }else{
            $user = User::where('role_id',3)->first();
        }
        Mail::to($user->email)->send(new contact_send($request->name, $request->phone, $request->type,$request->email, $request->comment));
    }

    public function profile(Request $request)
    {
        $role_id = Auth::user()->role->id;

        if($role_id == 2 || $role_id == 4 || $role_id == 5 || $role_id == 6 || $role_id == 8)
        {
            return view('users.profile.profile');
        }
        if($role_id == 3)
        {
            return view('users.profile.profile',['user' => User::find($request->user_id)]);
        }

    }

    public function registerUser(Request $request)
    {
        dd($request);
    }

    //
    //Users Ajax functions
    //
    //

    public function Ajax_SwitchHistory(Request $request)
    {
        $user = User::find($request->user_id);
        if(isset($request->type))
        {
        if($request->type == 1)
        {
            $view = view('users.profile.user_includes.buy_history.active_buys', [
                'user' => $user,
            ])->render();

        }
        elseif ($request->type == 2)
        {
            $view = view('users.profile.user_includes.buy_history.active_reserves', [
                'user' => $user,
            ])->render();

        }
        elseif ($request->type == 3)
        {
            $view = view('users.profile.user_includes.buy_history.inactive_buys', [
                'user' => $user,
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

    public function Ajax_sendConnectFranch(Request $request)
    {
        $user = User::find($request->user_id);

//        dd($user->franchise);
//        $user->franchise;
        Mail::to($user->franchise->email)->send(new SendUserConnectFranch($user, $request->comment, $request->phone));

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function Ajax_deleteReserve(Request $request)
    {
        $ticket = Ticket::find($request->id);
        $event = Event::find($ticket->event_id);
        $history = new History();
        $history->type1($request->comment, $history, $event);
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

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function Ajax_buyReserveTicket(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);
        $event = Event::find($ticket->event_id);
        $row = $request->row;
        $column = $request->column;

        if ($event->scheme == 1) {
            $hall = Hall::where('event_id', $event->id)->where('row', $row)->first();
                $ticket->ticket_price = isset($event->rate['stock'.$ticket->ticket_format.'_price']) && $event->rate['stock'.$ticket->ticket_format] > \Carbon\Carbon::now() ? $event->rate['stock'.$ticket->ticket_format.'_price'] : $event->rate['rate'.$ticket->ticket_format.'_price'];
                $ticket->type = 'buy';
                $count = Count_buys::where('user_id',Auth::user()->id)->where('event_id',$event->id)->first();
                if($count)
                {
                    $count->count = $count->count + 1;
                    $count->save();
                }
                else
                {
                    $count = new Count_buys();
                    $count->count = 1;
                    $count->user_id = Auth::user()->id;
                    $count->event_id = $event->id;
                    $count->save();
                }

                if (isset($event->rate['stock'.$ticket->ticket_format.'_price']) && $event->rate['stock'.$ticket->ticket_format] > Carbon::now())
                {
                    $ticket->discount = 'Скидка на тариф: '.$event->rate['rate'.$ticket->ticket_format];
                }
                $ticket->save();
                $temp = collect($hall->column);
                $column = $temp[$ticket->column];
                unset($column['reserve_id']);
                $column = array_merge($column, ['ticket_id' => $ticket->id]);
                $temp[$ticket->column] = $column;
                $hall->column = collect($temp);
                $hall->save();

                $financial = Financial::where('event_id', $event->id)->first();

                $income = ['user_id' => Auth::id(), 'sum' => $ticket->ticket_price, 'date' => Carbon::now(),'discount' => isset($ticket->discount) ? 1 : ''];
                $financial = $financial->add_income($income, $financial);

                if (Auth::user()->role->name == 'user' && isset(Auth::user()->franchise_id)) {
                    $franchise = User::find(Auth::user()->franchise_id);
                    $franchise_percent = ['user_id' => Auth::user()->franchise_id, 'sum' => (($ticket->ticket_price / 100) * $franchise->percent), 'date' => Carbon::now(), 'client_id' => Auth::user()->id];
                    $financial = $financial->add_franchise_percent($franchise_percent, $financial);

                    $EventFranchConnect = EventFranchFind::where('event_id', $event->id)->where('franchise_id', $franchise->id)->first();

                    if (!$EventFranchConnect) {
                        $EventFranchConnect = new EventFranchFind();
                        $EventFranchConnect->event_id = $event->id;
                        $EventFranchConnect->franchise_id = $franchise->id;
                        $EventFranchConnect->save();
                    }
                }
        }
        else if($event->scheme == 0)
        {
            $ticket->ticket_price = isset($event->rate['stock'.$ticket->ticket_format.'_price']) && $event->rate['stock'.$ticket->ticket_format] > \Carbon\Carbon::now() ? $event->rate['stock'.$ticket->ticket_format.'_price'] : $event->rate['rate'.$ticket->ticket_format.'_price'];
            $ticket->type = 'buy';

            $count = Count_buys::where('user_id',Auth::user()->id)->where('event_id',$event->id)->first();
            if($count)
            {
                $count->count = $count->count + 1;
                $count->save();
            }
            else
            {
                $count = new Count_buys();
                $count->count = 1;
                $count->user_id = Auth::user()->id;
                $count->event_id = $event->id;
                $count->save();
            }
            if (isset($event->rate['stock'.$ticket->ticket_format.'_price']) && $event->rate['stock'.$ticket->ticket_format] > Carbon::now())
            {
                $ticket->discount = 'Скидка на тариф: '.$event->rate['rate'.$ticket->ticket_format];
            }
            $ticket->save();

            $financial = Financial::where('event_id', $event->id)->first();

            $income = ['user_id' => Auth::id(), 'sum' => $ticket->ticket_price, 'date' => Carbon::now()];
            $financial = $financial->add_income($income, $financial);

            if (Auth::user()->role->name == 'user' && isset(Auth::user()->franchise_id)) {
                $franchise = User::find(Auth::user()->franchise_id);
                $franchise_percent = ['user_id' => Auth::user()->franchise_id, 'sum' => (($ticket->ticket_price / 100) * $franchise->percent), 'date' => Carbon::now(), 'client_id' => Auth::user()->id];
                $financial = $financial->add_franchise_percent($franchise_percent, $financial);

                $EventFranchConnect = EventFranchFind::where('event_id', $event->id)->where('franchise_id', $franchise->id)->first();

                if (!$EventFranchConnect) {
                    $EventFranchConnect = new EventFranchFind();
                    $EventFranchConnect->event_id = $event->id;
                    $EventFranchConnect->franchise_id = $franchise->id;
                    $EventFranchConnect->save();
                }
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function email_sent(Request $request) {
        return view('auth.verify_email');
    }

    public function confirm(User $user, $token) {
        if ($user->confirm($token)) {
            AMOController::createAmoClient($user);
            redirect()->route('login');
            $message = 'Your e-mail address verified.';

        } else {

            $message = 'Your e-mail address is either already confirmed or your confirmation token is wrong.';

        }
    return redirect()->route('login')->withMessage($message);
    }

}
