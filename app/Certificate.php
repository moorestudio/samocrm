<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $casts = [
        'title_style' => 'collection',
        'date_style' => 'collection',
        'name_style' => 'collection',
    ];
}
