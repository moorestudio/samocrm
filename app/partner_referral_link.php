<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class partner_referral_link extends Model
{

    protected $fillable = ['user_id','url_link'];

    protected static function boot()
    {
    parent::boot();
    static::creating(function (partner_referral_link $model) {
    $model->generateCode();
    });
    }
    private function generateCode()
    {
        $this->code = (string)Uuid::uuid1();
        $this->url_link = url("partner_new/".$this->referral_event_id."?part_ref=".$this->code);
    }

    public function getLinkAttribute()
    {
        return url($this->program->uri) . '?part_ref=' . $this->code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function relationships()
    {
        return $this->hasMany(partner_referral_relationship::class);
    }
}
