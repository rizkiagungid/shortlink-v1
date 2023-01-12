<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class V110 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->integer('expiration_clicks')->after('expiration_url')->default(0)->nullable();
            $table->tinyInteger('target_type')->after('title')->default(0)->nullable();
            $table->text('rotation_target')->after('platform_target')->nullable();
            $table->tinyInteger('last_rotation')->after('rotation_target')->default(0)->nullable();
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->tinyInteger('option_global_domains')->after('option_utm')->default(1)->nullable();
            $table->tinyInteger('option_link_rotation')->after('option_global_domains')->default(1)->nullable();
            $table->tinyInteger('option_deep_links')->after('option_link_rotation')->default(1)->nullable();
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
