<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class V350 extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pixels', function($table) {
            $table->renameColumn('pixel_id', 'value');
        });

        DB::update("UPDATE `domains` SET `name` = REPLACE(REPLACE(`name`, 'http://', ''), 'https://', '')");

        Schema::table('users', function (Blueprint $table) {
            $table->text('billing_information')->nullable()->after('role');
            $table->timestamp('plan_ends_at')->nullable()->after('role');
            $table->timestamp('plan_trial_ends_at')->nullable()->after('role');
            $table->timestamp('plan_recurring_at')->nullable()->after('role');
            $table->timestamp('plan_created_at')->nullable()->after('role');
            $table->string('plan_subscription_status', 32)->nullable()->after('role');
            $table->string('plan_subscription_id', 128)->nullable()->after('role');
            $table->string('plan_payment_processor', 32)->nullable()->after('role');
            $table->string('plan_interval', 16)->nullable()->after('role');
            $table->string('plan_currency', 12)->nullable()->after('role');
            $table->string('plan_amount', 32)->nullable()->after('role');
            $table->integer('plan_id')->unsigned()->default(1)->after('role')->index('plan_id');
            $table->smallInteger('default_stats')->default(1)->change();

            $table->dropColumn(['stripe_id', 'card_last_four', 'card_brand', 'trial_ends_at']);
        });

        Schema::drop('subscriptions');
        Schema::drop('subscription_items');

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index('user_id');
            $table->unsignedInteger('plan_id')->index('plan_id');
            $table->string('payment_id', 128)->index('payment_id');
            $table->string('invoice_id', 128)->nullable()->index('invoice_id');
            $table->string('processor', 32)->index('processor');
            $table->string('amount', 32);
            $table->string('currency', 12);
            $table->string('interval', 16)->index('interval');
            $table->string('status', 16)->index('status');
            $table->text('product')->nullable();
            $table->text('coupon')->nullable();
            $table->text('tax_rates')->nullable();
            $table->text('seller')->nullable();
            $table->text('customer')->nullable();
            $table->timestamps();
        });

        Schema::table('cronjobs', function(Blueprint $table)
        {
            $table->increments('id')->change();
        });

        Schema::table('pages', function(Blueprint $table) {
            $table->renameColumn('title', 'name');
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::create('coupons', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->index('name');
            $table->string('code')->index('code');
            $table->boolean('type')->index('type');
            $table->decimal('percentage', 5, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('days')->nullable();
            $table->integer('redeems')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tax_rates', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->index('name');
            $table->boolean('type')->index('type');
            $table->decimal('percentage', 5, 2);
            $table->text('regions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->increments('id')->change();
            $table->string('amount_month', 32)->default(0)->change();
            $table->string('amount_year', 32)->default(0)->change();
            $table->text('tax_rates')->after('coupons')->nullable();
            $table->text('features')->after('visibility')->nullable();
            $table->timestamps();
        });

        $featuresList = ['option_api', 'option_links', 'option_spaces', 'option_domains', 'option_pixels', 'option_stats', 'option_targeting', 'option_expiration', 'option_password', 'option_disabled', 'option_utm', 'option_global_domains', 'option_deep_links', 'option_data_export'];
        $features = [];
        foreach (DB::table('plans')->select('*')->cursor() as $row) {
            foreach ($featuresList as $feature) {
                $features[str_replace('option_', '', $feature)] = $row->$feature;
            }

            DB::statement("UPDATE `plans` SET `features` = :features, `amount_month` = :amount_month, `amount_year` = :amount_year WHERE `id` = :id", ['features' => json_encode($features), 'id' => $row->id, 'amount_month' => (!in_array(strtoupper($row->currency), config('currencies.zero_decimals')) ? $row->amount_month / 100 : $row->amount_month), 'amount_year' => (!in_array(strtoupper($row->currency), config('currencies.zero_decimals')) ? $row->amount_year / 100 : $row->amount_year)]);
        }

        Schema::table('plans', function (Blueprint $table) use ($featuresList) {
            $table->dropColumn(array_merge(['product', 'plan_month', 'plan_year'], $featuresList));
        });

        DB::update("UPDATE `plans` SET `tax_rates` = '[]', `coupons` = '[]'");

        $settings = array_combine(['registration', 'billing_vendor', 'billing_address', 'billing_city', 'billing_state', 'billing_postal_code', 'billing_country', 'billing_phone', 'billing_vat_number'], ['registration_registration', 'invoice_vendor', 'invoice_address', 'invoice_city', 'invoice_state', 'invoice_postal_code', 'invoice_country', 'invoice_phone', 'invoice_vat_number']);

        $sqlQuery = null;
        foreach($settings as $new => $old) {
            $sqlQuery .= "WHEN `name` = '" . $old . "' THEN '" . $new . "' ";
        }

        DB::update("UPDATE `settings` SET `name` = CASE " . $sqlQuery . " END WHERE `name` IN ('" . implode("', '", $settings) . "')");

        DB::table('settings')->insert(
            [
                [
                    'name' => 'paypal',
                    'value' => '0'
                ], [
                    'name' => 'paypal_mode',
                    'value' => 'sandbox'
                ], [
                    'name' => 'paypal_client_id',
                    'value' => ''
                ], [
                    'name' => 'paypal_secret',
                    'value' => ''
                ], [
                    'name' => 'paypal_webhook_id',
                    'value' => ''
                ], [
                    'name' => 'coinbase',
                    'value' => '0'
                ], [
                    'name' => 'coinbase_key',
                    'value' => ''
                ], [
                    'name' => 'coinbase_wh_secret',
                    'value' => ''
                ], [
                    'name' => 'bank',
                    'value' => '0'
                ], [
                    'name' => 'bank_account_owner',
                    'value' => ''
                ], [
                    'name' => 'bank_account_number',
                    'value' => ''
                ], [
                    'name' => 'bank_name',
                    'value' => ''
                ], [
                    'name' => 'bank_routing_number',
                    'value' => ''
                ], [
                    'name' => 'bank_iban',
                    'value' => ''
                ], [
                    'name' => 'bank_bic_swift',
                    'value' => ''
                ], [
                    'name' => 'billing_invoice_prefix',
                    'value' => ''
                ], [
                    'name' => 'announcement_guest',
                    'value' => ''
                ], [
                    'name' => 'announcement_guest_type',
                    'value' => 'info'
                ], [
                    'name' => 'announcement_guest_id',
                    'value' => 'cwUOUj7dQZZzJstX'
                ], [
                    'name' => 'announcement_user',
                    'value' => ''
                ], [
                    'name' => 'announcement_user_type',
                    'value' => 'info'
                ], [
                    'name' => 'announcement_user_id',
                    'value' => 'p0VIvAg0FU26HN2y'
                ],
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
