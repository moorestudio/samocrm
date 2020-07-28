<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    protected $table = 'halls';
    protected $fillable = ['row', 'column'];
    protected $casts = [
        'column' => 'collection',
    ];

}
