<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutHall extends Model
{
    protected $table = 'out_halls';
    protected $fillable = ['row', 'column','width','height'];
    protected $casts = [
        'column' => 'collection',
    ];

}
