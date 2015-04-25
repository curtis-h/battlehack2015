<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('trackings', function(Blueprint $table) {
	        $table->increments('id');
	        $table->timestamps();
	        $table->string('user_id');
	        $table->string('device_id');
	        $table->string('advert_id');
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('trackings');
	}

}
