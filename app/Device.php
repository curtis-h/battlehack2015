<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Device extends Eloquent
{
    protected $visible = ['id', 'lat', 'lng'];
}