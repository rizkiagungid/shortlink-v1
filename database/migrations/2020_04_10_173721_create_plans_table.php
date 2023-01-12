<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePlansTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plans', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('product', 255);
			$table->string('name', 255);
			$table->text('description');
			$table->integer('trial_days')->nullable();
			$table->string('currency', 12);
			$table->tinyInteger('decimals')->nullable();
			$table->string('plan_month', 255)->nullable();
			$table->string('plan_year', 255)->nullable();
			$table->integer('amount_month')->nullable();
			$table->integer('amount_year')->nullable();
			$table->tinyInteger('visibility')->nullable();
			$table->string('color', 32);
			$table->tinyInteger('option_api')->nullable();
			$table->integer('option_links')->nullable();
			$table->integer('option_spaces')->nullable();
			$table->integer('option_domains')->nullable();
			$table->tinyInteger('option_stats')->nullable();
			$table->tinyInteger('option_geo')->nullable();
			$table->tinyInteger('option_platform')->nullable();
			$table->tinyInteger('option_expiration')->nullable();
			$table->tinyInteger('option_password')->nullable();
			$table->tinyInteger('option_disabled')->nullable();
			$table->tinyInteger('option_utm')->nullable();
			$table->softDeletes();
		});

        DB::table('plans')->insert([
            'product' => '',
            'name' => 'Default',
            'description' => 'The plan\'s awesome description.',
            'trial_days' => NULL,
            'currency' => '',
            'decimals' => NULL,
            'plan_month' => '',
            'plan_year' => '',
            'amount_month' => 0,
            'amount_year' => 0,
            'visibility' => 1,
            'color' => '#ef698b',
            'option_api' => 1,
            'option_links' => -1,
            'option_spaces' => -1,
            'option_domains' => -1,
            'option_stats' => 1,
            'option_geo' => 1,
            'option_platform' => 1,
            'option_expiration' => 1,
            'option_password' => 1,
            'option_disabled' => 1,
            'option_utm' => 1
        ]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('plans');
	}
}
