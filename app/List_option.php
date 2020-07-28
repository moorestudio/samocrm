<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class List_option extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'kind', 'type','options','access'
    ];

    protected $casts = [
        'options' => 'collection',
        'access' => 'collection',
    ];
}
