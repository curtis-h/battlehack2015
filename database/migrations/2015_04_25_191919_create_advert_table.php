<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
        Schema::create('adverts', function(Blueprint $table) {
            $table->increments('id');
            // what type of advert this is
            $table->string('product');
            // specific to the mime type image / video / 
            $table->string('type'); 		//-- Video type
            $table->string('data');			//-- Datas
            $table->string('amazon_id');    // item id for amazon for linking
            $table->integer('duration')->default('1');
            $table->float('amount');
            $table->timestamps();
        });
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('adverts');
	}

}
