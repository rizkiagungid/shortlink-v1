<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class V430 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex('title');
            $table->index('name', 'name');
        });

        $language = DB::table('languages')->select('code')->where('default', '=', 1)->first();

        $settings = array_combine(['custom_js', 'bad_words', 'gsb', 'gsb_key'], ['tracking_code', 'short_bad_words', 'short_gsb', 'short_gsb_key']);

        $sqlQuery = null;
        foreach($settings as $new => $old) {
            $sqlQuery .= "WHEN `name` = '" . $old . "' THEN '" . $new . "' ";
        }

        DB::update("UPDATE `settings` SET `name` = CASE " . $sqlQuery . " END WHERE `name` IN ('" . implode("', '", $settings) . "')");

        DB::table('settings')->insert(
            [
                ['name' => 'locale', 'value' => $language->code],
                ['name' => 'request_proxy', 'value' => null],
                ['name' => 'request_timeout', 'value' => 5],
                ['name' => 'request_user_agent', 'value' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36']
            ]
        );

        Schema::drop('languages');

        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedInteger('position')->after('visibility')->nullable()->default(0);
            $table->dropColumn('decimals');
            $table->dropColumn('color');
        });
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
