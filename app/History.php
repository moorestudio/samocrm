<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class History extends Model
{
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type1($comment, $history, $event)
    {
        $history->event_name = 'Удаление брони';
        $history->info = $comment;
        $history->event_id = $event->id;
        $history->user_id = Auth::user()->id;
        $history->type = 1;
        $history->save();

        return $history;
    }
}
