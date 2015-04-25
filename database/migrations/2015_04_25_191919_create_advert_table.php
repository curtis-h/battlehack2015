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
            $table->string('product');
            $table->string('type'); 		//-- Video type
            $table->string('data');			//-- Datas
            $table->string('amazon_id');    // item id for amazon for linking
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
