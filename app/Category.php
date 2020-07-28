<?php

namespace App;
use App\Event;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function events(){
        return $this->hasMany(Event::class);
    }
    public function eventQuantity(){
      $events = count(Event::where('category_id',$this->id)->get());
      return $events;
    }
}
