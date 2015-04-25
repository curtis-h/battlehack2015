<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Tracking extends Eloquent
{
    protected $fillable = ['user_id', 'device_id'];
    
    public function device() {
        return $this->hasOne('App\Device', 'devide_id');
    }
    
}