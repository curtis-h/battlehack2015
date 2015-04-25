<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Tracking extends Eloquent
{
    protected $fillable = ['user_id', 'device_id'];
    
    public function device() {
        return $this->belongsTo('App\Device');
    }
    
    public function advert() {
        return $this->belongsTo('App\Advert');
    }
}