<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Tracking extends Eloquent
{
    protected $guarded = [];
    protected $visible = ['device', 'user_id', 'device_id', 'advert_id'];
    protected $appends = ['device'];
    
    public function device() {
        return $this->belongsTo('App\Device');
    }
    
    public function getDeviceAttribute() {
        return $this->device();
    }
    
    public function advert() {
        return $this->belongsTo('App\Advert');
    }
}