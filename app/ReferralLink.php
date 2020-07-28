<?php

namespace App;
use App\Event;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
class ReferralLink extends Model
{

    protected $fillable = ['user_id', 'referral_event_id','url_link'];

    protected static function boot()
    {
    parent::boot();
    static::creating(function (ReferralLink $model) {
    $model->generateCode();
    });
    }
        private function generateCode()
    {
        $this->code = (string)Uuid::uuid1();
//        $this->url_link = url("register"."?ref=".$this->code);
        $this->url_link = url("info/".$this->referral_event_id."?ref=".$this->code);
    }

    // public static function getReferral($user, $program)
    // {
    //     return static::firstOrCreate([
    //         'user_id' => $user->id,
    //         'referral_event_id' => $program->id
    //     ]);
    // }

    // public static function getReferral($user)
    // {


    //     return url("register"."?ref=".$this->code)
    // }



    public function getLinkAttribute()
    {
        return url($this->program->uri) . '?ref=' . $this->code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Event::class, 'referral_event_id');
    }

    public function relationships()
    {
        return $this->hasMany(ReferralRelationship::class);
    }
}
