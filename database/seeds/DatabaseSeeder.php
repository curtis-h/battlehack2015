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
            'data'    => "http://www.bradwellbutchery.co.uk/images/sausage-sub.jpg"
        ]);
        
        Advert::create([
            'product' => 2,
            'type'    => 'image',
            'data'    => "http://static.guim.co.uk/sys-images/Guardian/Pix/pictures/2014/4/11/1397210130748/Spring-Lamb.-Image-shot-2-011.jpg"
        ]);
        
        $this->command->info("Adverts table seeded");
    }
}
