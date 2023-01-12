<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('links', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->nullable()->index('user_id');
			$table->string('alias', 255)->index('alias');
			$table->string('url', 2048);
			$table->string('title', 255)->nullable();
			$table->text('geo_target')->nullable();
			$table->text('platform_target')->nullable();
			$table->string('password')->nullable();
			$table->tinyInteger('disabled')->default(0);
			$table->tinyInteger('public')->default(0);
			$table->string('expiration_url', 2048)->nullable();
			$table->integer('clicks')->nullable()->default(0)->index('clicks');
			$table->integer('space_id')->nullable()->index('space_id');
			$table->integer('domain_id')->nullable()->index('domain_id');
			$table->timestamp('ends_at')->nullable();
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
		Schema::drop('links');
	}
}
