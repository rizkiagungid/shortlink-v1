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
     * @return void
     */
    public function up()
    {
        DB::table('settings')->insert([
            ['name' => 'registration_tfa', 'value' => 0],
            ['name' => 'login_tfa', 'value' => 0],
            ['name' => 'webhook_user_created', 'value' => null],
            ['name' => 'webhook_user_updated', 'value' => null],
            ['name' => 'webhook_user_deleted', 'value' => null],
            ['name' => 'razorpay', 'value' => null],
            ['name' => 'razorpay_key', 'value' => null],
            ['name' => 'razorpay_secret', 'value' => null],
            ['name' => 'razorpay_wh_secret', 'value' => null],
            ['name' => 'paystack', 'value' => null],
            ['name' => 'paystack_key', 'value' => null],
            ['name' => 'paystack_secret', 'value' => null],
            ['name' => 'cryptocom', 'value' => null],
            ['name' => 'cryptocom_key', 'value' => null],
            ['name' => 'cryptocom_secret', 'value' => null],
            ['name' => 'cryptocom_wh_secret', 'value' => null],
            ['name' => 'logo_dark', 'value' => 'logo_dark.svg']
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('tfa_code_created_at')->after('billing_information')->nullable();
            $table->string('tfa_code')->after('billing_information')->nullable();
            $table->smallInteger('tfa')->after('billing_information')->nullable();
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
};
