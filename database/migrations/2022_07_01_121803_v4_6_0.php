<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return string|void
     */
    public function up()
    {
        $envFile = file_get_contents(base_path('.env'));

        if (!str_contains($envFile, 'APP_INSTALLED')) {
            file_put_contents(base_path('.env'), "\n\nAPP_INSTALLED=true", FILE_APPEND);
        }

        $settings = array_combine(['webhook_user_created', 'webhook_user_updated', 'webhook_user_deleted'], ['webhook_user_store', 'webhook_user_update', 'webhook_user_destroy']);

        $sqlQuery = null;
        foreach($settings as $new => $old) {
            $sqlQuery .= "WHEN `name` = '" . $old . "' THEN '" . $new . "' ";
        }

        DB::update("UPDATE `settings` SET `name` = CASE " . $sqlQuery . " END WHERE `name` IN ('" . implode("', '", $settings) . "')");
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
};
