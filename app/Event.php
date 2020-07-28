<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\CurrencyNames;

class Event extends Model
{
    protected $casts = [
        'rate' => 'collection',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function free_seats()
    {
        $_taken = count($this->tickets()->whereIn('type',['buy','reserved','dones'])->get());
        return $this->ticket_count - $_taken;
    }

    public function financial()
    {
        return $this->hasOne(Financial::class);
    }

    public function eventFormat($event, $format)
    {
        return $event->rate[$format][0];
    }

    public function Histories()
    {
        return $this->hasMany(History::class);
    }

    public function TicketDesign()
    {
        return $this->hasOne(TicketDesign::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function normalDate(){
      $date = Carbon::parseFromLocale($this->date, 'ru')->format('d-m-Y');
      return $date;
    }
    public function normalEndDate(){
      $date = Carbon::parseFromLocale($this->end_date, 'ru')->format('d-m-Y');
      return $date;
    }

    public function normalTime(){
      $time = Carbon::parseFromLocale($this->date, 'ru')->format('H:i');
      return $time;
    }

    public function come(){
      $num = 0;
      $tickets = Ticket::where('event_id',$this->id)->where('type','done')->get();
      if($tickets){
        $num = count($tickets);
      }
      return $num;
    }
    public function notCome(){
      $num = 0;
      $tickets = Ticket::where('event_id',$this->id)->where('type','buy')->get();
      if($tickets){
        $num = count($tickets);
      }
      return $num;
    }
    public function reserved(){
      $num = 0;
      $tickets = Ticket::where('event_id',$this->id)->where('type','reserve')->get();
      if($tickets){
        $num = count($tickets);
      }
      return $num;
    }
    public function soldTicketsQuantity(){
      $num = 0;
      $tickets = Ticket::where('event_id',$this->id)->whereIn('type',['done','buy'])->get();
      if($tickets){
        $num = count($tickets);
      }
      return $num;
    }
    public function soldTicketsPrice(){
      $num = 0;
      $tickets = Ticket::where('event_id',$this->id)->whereIn('type',['done','buy'])->get();
      if($tickets){
        foreach($tickets as $ticket){
          $num += $ticket->ticket_price;
        }
      }
      return $num;
    }
    public function getCurrencyName(){
      $name = "";
      if($this->currency){
        $currency = CurrencyNames::find($this->currency);
        if($currency){
          $name = $currency->currency;
        }
      } 
      return $name;
    }
}
