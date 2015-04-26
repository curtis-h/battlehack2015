<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Advert;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
		$this->adverts();
	}

	
    function adverts() {
        DB::table('adverts')->delete();
        
        Advert::create([
            'product' => 1,
            'type'    => 'image',
            'data'    => "http://www.bradwellbutchery.co.uk/images/sausage-sub.jpg",
            'amount'  => '0'
        ]);
        
        Advert::create([
            'product' => 1,
            'type'    => 'image',
            'data'    => "http://static.guim.co.uk/sys-images/Guardian/Pix/pictures/2014/4/11/1397210130748/Spring-Lamb.-Image-shot-2-011.jpg",
            'amount'  => '0'
        ]);
        
        Advert::create([
            'product' => 1,
            'type'    => 'image',
            'data'    => "http://battlehack.jakelprice.com/images/flowers.png",
            'amount'  => '0'
        ]);
        
        Advert::create([
            'product' => 1,
            'type'    => 'image',
            'data'    => "http://battlehack.jakelprice.com/images/iphone.png",
            'amount'  => '0'
        ]);
        
        Advert::create([
            'product' => 1,
            'type'    => 'image',
            'data'    => "http://battlehack.jakelprice.com/images/mac.png",
            'amount'  => '0'
        ]);
        
        Advert::create([
            'product' => 1,
            'type'    => 'image',
            'data'    => "http://battlehack.jakelprice.com/images/tesla_ad1.png",
            'amount'  => '0'
        ]);
        
        
        $this->command->info("Adverts table seeded");
    }
}
