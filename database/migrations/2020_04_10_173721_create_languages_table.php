<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLanguagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('languages', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 64)->unique('code');
			$table->string('name', 255);
			$table->string('dir', 32);
			$table->tinyInteger('default')->nullable()->default(0);
		});

        DB::table('languages')->insert([
            'code'      => 'en',
            'name'      => 'English',
            'dir'       => 'ltr',
            'default'   => 1
        ]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('languages');
	}
}
