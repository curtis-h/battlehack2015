<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Pusher;

class Advert extends Eloquent
{
    public static function send($person, $device) {
        //-- Look for an advert for this person
        //-- TODO: Need to get working :)
        //-- TODO: Mike and Curtis
        if ($person == "mike") {
            $a = Advert::where('product', 1)->first();
        } else {
            $a = Advert::where('product', 2)->first();
        }
    
        //-- If no person - lets decide on an ad anyway
        $data = [
            'display'  => $device,
            'type'     => $a->type,
            'data'     => $a->data,
            'duration' => $a->duration
        ];
        
        self::pushtopusher($data);
        return $a->id;
    }
    
    
    public static function random($device) {
        $a = Advert::where('product', 1)->get()->random(1);
        $data = [
            'display'  => $device,
            'type'     => $a->type,
            'data'     => $a->data,
            'duration' => $a->duration
        ];
        
        self::pushtopusher($data);
        return $a->id;
    }
    
    
    private static function pushtopusher($data) {
        $pusher = new Pusher($_ENV['PUSHER_KEY'], $_ENV['PUSHER_SECRET'], $_ENV['PUSHER_ID']);
        $pusher->trigger('adverts', 'display_advert', $data);
    }
}