<?php

namespace App;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;

class TicketDesign extends Model
{
    protected $casts = [
        'title_style' => 'collection',
        'address_style' => 'collection',
        'date_style' => 'collection',
        'image_style' => 'collection',
        'city_style' => 'collection',
        'price_style' => 'collection',
        'places_style' => 'collection',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }



}
