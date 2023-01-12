<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stats', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('link_id')->index('link_id');
			$table->integer('user_id')->index('user_id');
			$table->string('referrer', 255)->nullable()->index('referrer');
			$table->string('platform', 64)->nullable();
			$table->string('browser', 64)->nullable();
			$table->string('device', 64);
			$table->char('country', 2)->nullable();
			$table->char('language', 2)->nullable();
			$table->timestamp('created_at')->useCurrent()->index('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stats');
	}
}
