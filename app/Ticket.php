<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Event;
class Ticket extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function dateFormat($date){
        $date = Carbon::make($this->created_at)->format($date);
        return $date;
    }
    public static function hasTicket($event,$user){
        $message = "";
        $ticket = Ticket::where('event_id',$event)->where('user_id',$user)->whereIn('type',['buy','reserve'])->first();
        if($ticket){
            if($ticket->type == "buy"){
                $message = "Вы уже купили билет на это мероприятие";
            }elseif($ticket->type == "reserve"){
                $message = "Вы уже забронировали билет на это мероприятие";
            }
        }

        return $message;
    }
    public function format(){
        $event = Event::find($this->event_id);
        return $event->rate[$this->ticket_format][0];
    }
}
