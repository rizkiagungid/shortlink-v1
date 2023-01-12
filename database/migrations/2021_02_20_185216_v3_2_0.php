<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class V320 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('old_stats');

        Schema::create('cronjobs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 64);
            $table->timestamps();
        });

        DB::table('settings')->insert(
            [
                [
                    'name' => 'cronjob_key',
                    'value' => Str::random(32)
                ]
            ]
        );
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
