<?php

namespace App\Http\Controllers;
use App\Information;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Event;
use App\Financial;
use App\Hall;
use App\History;
use App\User;
use App\Ticket;
use App\Certificate;
use App\ReferralLink;
use App\ReferralRelationship;
use App\CurrencyNames;
use Session;
use Auth;
use App\Mail\SendCertificates;
use App\Mail\SendNotification;
use App\Mail\SendNotificationAboutWithdrawReserve;
use Illuminate\Support\Facades\Mail;

class CronController extends Controller
{
    public function send_certificate(){
      $events = Event::all();
      $today = Carbon::now()->format('Y-m-d');
      foreach($events as $key=>$event){
          $date = Carbon::parseFromLocale($event->date, 'ru')->format('Y-m-d');
          $time = Carbon::now()->format('H:i:s');
          $check_time = $event->newsletter_time;
          if($today == $date and $check_time <= $time){
            $tickets = Ticket::where('type','done')->where('event_id',$event->id)->get();
            foreach($tickets as $ticket){
              try{
                if($ticket->send_certificate !=1 ){
                  $ticket_old = Ticket::find($ticket->id);
                  $user = User::find($ticket->user_id);
                  if($user){
                    Mail::to($user->email)->send(new SendCertificates($ticket->id,$user->name,$event->title));
                    $ticket_old->send_certificate = 1;
                  }
                  $ticket_old->save();
                }
              }catch(\Exception $e) {
                continue;
              }
            }

          }

      }
    }
    public function notification(){
      $events = Event::all();
      $today = Carbon::now()->format('Y-m-d H:i');
      foreach($events as $key=>$event){
          $date = Carbon::parseFromLocale($event->date, 'ru')->subDays(1)->subMinutes(5)->format('Y-m-d H:i');
          if($today >= $date and $event->send_notification == 0){
            $tickets = Ticket::where('type','done')->where('event_id',$event->id)->get();
            foreach($tickets as $ticket){
              try{
                $ticket_old = Ticket::find($ticket->id);
                $user = User::find($ticket->user_id);
                if($user){
                  Mail::to($user->email)->send(new SendNotification($event,$user));
                }
              }catch(\Exception $e) {
                continue;
              }
            }
            $event->send_notification = 1;
            $event->save();
          }

      }
    }

    public function withdraw_reservation(){
      $events = Event::all();
      $today = Carbon::now()->format('Y-m-d');
      $time = Carbon::now()->format('H:i:s');
      foreach($events as $key=>$event){
          $date = Carbon::parseFromLocale($event->date, 'ru')->subDays(7)->format('Y-m-d');
          $event_time = Carbon::parseFromLocale($event->date, 'ru')->subHours(1)->format('H:i:s');
          $tickets = Ticket::where('type','reserve')->where('event_id',$event->id)->get();
          if($today >= $date and $time >= $event_time){
            foreach($tickets as $ticket){
              try{
                $ticket_old = Ticket::find($ticket->id);
                $user = User::find($ticket_old->user_id);
                $history = new History();
                $history->type1('Автоматическое снятие брони', $history, $event);
                $hall = Hall::where('event_id',$event->id)->where('row',$ticket_old->row)->first();
                $temp = collect($hall->column);
                $column = $temp[$ticket_old->column];
                unset($column['reserve_id']);
                $temp[$ticket->column] = $column;
                $hall->column = $temp;
                $hall->save();
                $ticket_old->delete();
              }catch(\Exception $e) {
                continue;
              }
            }
          }else{
            foreach($tickets as $ticket){
              try{
                $ticket_old = Ticket::find($ticket->id);
                $reserve_date = Carbon::parse($ticket_old->created_at)->addDays(5)->format('Y-m-d');
                $reserve_date_next = Carbon::parse($ticket_old->created_at)->addDays(6)->format('Y-m-d');
                $user = User::find($ticket->user_id);
                if($reserve_date == $today and $ticket_old->send_notification==0){
                  if($user){
                    Mail::to($user->email)->send(new SendNotificationAboutWithdrawReserve($event,$user));
                  }
                  $ticket_old->send_notification = 1;
                  $ticket_old->save();
                }elseif($reserve_date_next == $today){
                  $history = new History();
                  $history->type1('Автоматическое снятие брони', $history, $event);
                  $hall = Hall::where('event_id',$event->id)->where('row',$ticket_old->row)->first();
                  $temp = collect($hall->column);
                  $column = $temp[$ticket_old->column];
                  unset($column['reserve_id']);
                  $temp[$ticket->column] = $column;
                  $hall->column = $temp;
                  $hall->save();
                  $ticket_old->delete();
                }
              }catch(\Exception $e) {
                continue;
              }
            }
          }

      }
    }
}
