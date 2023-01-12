<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class V410 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = \Illuminate\Support\Carbon::now();

        DB::table('domains')->insert([
            'name' => parse_url(config('app.url'))['host'],
            'user_id' => 0,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $domain = DB::table('domains')->orderByDesc('id')->first();

        DB::update("UPDATE `settings` SET `value` = '" . $domain->id . "' WHERE `name` = 'short_domain'");

        DB::update("UPDATE `links` SET `domain_id` = '" . $domain->id . "' WHERE `domain_id` is null or `domain_id` = 0");

        DB::update("UPDATE `users` SET `default_domain` = '" . $domain->id . "' WHERE `default_domain` is null or `default_domain` = 0");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
