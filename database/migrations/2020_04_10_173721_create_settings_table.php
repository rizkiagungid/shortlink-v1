<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSettingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->string('name', 128)->primary();
			$table->text('value')->nullable();
		});

        DB::table('settings')->insert(
            [
                [
                    'name' => 'captcha_contact',
                    'value' => '0'
                ],
                [
                    'name' => 'captcha_registration',
                    'value' => '0'
                ],
                [
                    'name' => 'captcha_secret_key',
                    'value' => ''
                ],
                [
                    'name' => 'captcha_shorten',
                    'value' => '0'
                ],
                [
                    'name' => 'captcha_site_key',
                    'value' => ''
                ],
                [
                    'name' => 'contact_email',
                    'value' => ''
                ],
                [
                    'name' => 'email_address',
                    'value' => ''
                ],
                [
                    'name' => 'email_driver',
                    'value' => 'log'
                ],
                [
                    'name' => 'email_encryption',
                    'value' => 'tls'
                ],
                [
                    'name' => 'email_host',
                    'value' => ''
                ],
                [
                    'name' => 'email_password',
                    'value' => ''
                ],
                [
                    'name' => 'email_port',
                    'value' => ''
                ],
                [
                    'name' => 'email_username',
                    'value' => ''
                ],
                [
                    'name' => 'favicon',
                    'value' => 'favicon.png'
                ],
                [
                    'name' => 'index',
                    'value' => ''
                ],
                [
                    'name' => 'invoice_address',
                    'value' => ''
                ],
                [
                    'name' => 'invoice_city',
                    'value' => ''
                ],
                [
                    'name' => 'invoice_country',
                    'value' => ''
                ],
                [
                    'name' => 'invoice_phone',
                    'value' => ''
                ],
                [
                    'name' => 'invoice_postal_code',
                    'value' => ''
                ],
                [
                    'name' => 'invoice_state',
                    'value' => ''
                ],
                [
                    'name' => 'invoice_vat_number',
                    'value' => ''
                ],
                [
                    'name' => 'invoice_vendor',
                    'value' => ''
                ],
                [
                    'name' => 'legal_cookie_url',
                    'value' => ''
                ],
                [
                    'name' => 'legal_privacy_url',
                    'value' => ''
                ],
                [
                    'name' => 'legal_terms_url',
                    'value' => ''
                ],
                [
                    'name' => 'license_key',
                    'value' => NULL,
                ],
                [
                    'name' => 'license_type',
                    'value' => NULL,
                ],
                [
                    'name' => 'logo',
                    'value' => 'logo.svg'
                ],
                [
                    'name' => 'registration_registration',
                    'value' => '1'
                ],
                [
                    'name' => 'registration_verification',
                    'value' => '1'
                ],
                [
                    'name' => 'short_bad_words',
                    'value' => ''
                ],
                [
                    'name' => 'short_guest',
                    'value' => '1'
                ],
                [
                    'name' => 'social_facebook',
                    'value' => ''
                ],
                [
                    'name' => 'social_instagram',
                    'value' => ''
                ],
                [
                    'name' => 'social_twitter',
                    'value' => ''
                ],
                [
                    'name' => 'social_youtube',
                    'value' => ''
                ],
                [
                    'name' => 'stripe',
                    'value' => '0'
                ],
                [
                    'name' => 'stripe_key',
                    'value' => ''
                ],
                [
                    'name' => 'stripe_secret',
                    'value' => ''
                ],
                [
                    'name' => 'stripe_wh_secret',
                    'value' => ''
                ],
                [
                    'name' => 'tagline',
                    'value' => 'URL shortening for the new generation.'
                ],
                [
                    'name' => 'theme',
                    'value' => '0'
                ],
                [
                    'name' => 'timezone',
                    'value' => 'UTC'
                ],
                [
                    'name' => 'title',
                    'value' => 'phpShort'
                ],
                [
                    'name' => 'tracking_code',
                    'value' => ''
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
		Schema::drop('settings');
	}
}
