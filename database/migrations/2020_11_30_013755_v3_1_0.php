<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class V310 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('stats', 'old_stats');

        Schema::create('stats', function (Blueprint $table) {
            $table->integer('link_id');
            $table->enum('name', ['browser', 'platform', 'device', 'clicks', 'country', 'city', 'referrer', 'language', 'clicks_hours']);
            $table->string('value', 255);
            $table->bigInteger('count')->default(1);
            $table->date('date');
            $table->primary(['link_id', 'name', 'value', 'date']);
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->tinyInteger('option_data_export')->after('option_deep_links')->default(1)->nullable();
            $table->tinyInteger('option_targeting')->after('option_stats')->default(1)->nullable();
            $table->integer('option_pixels')->after('option_domains')->default(-1)->nullable();
            $table->dropColumn('option_geo');
            $table->dropColumn('option_platform');
            $table->dropColumn('option_link_rotation');
        });

        Schema::table('links', function (Blueprint $table) {
            $table->text('password')->change();
            $table->text('privacy_password')->after('public')->nullable();
            $table->text('description')->after('title')->nullable();
            $table->text('image')->after('description')->nullable();
            $table->renameColumn('geo_target', 'country_target');
            $table->renameColumn('public', 'privacy');
            $table->text('language_target')->after('platform_target')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('default_stats', 'default_stats');
        });

        Schema::create('pixels', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255)->index('name');
            $table->enum('type', ['adroll', 'google-ads', 'bing', 'facebook', 'google-analytics', 'google-tag-manager', 'linkedin', 'pinterest', 'quora', 'twitter']);
            $table->string('pixel_id', 255);
            $table->integer('user_id')->index('user_id');
            $table->timestamps();
            $table->unique(['name', 'user_id']);
        });

        Schema::create('link_pixel', function (Blueprint $table) {
            $table->integer('link_id')->index('link_id');
            $table->integer('pixel_id')->index('pixel_id');
            $table->unique(['link_id', 'pixel_id']);
        });

        foreach (DB::table('old_stats')->select('*')->cursor() as $row) {
            $data = $values = [];

            $data['referrer'] = $row->referrer ?? null;
            $data['browser'] = $row->browser ?? null;
            $data['platform'] = $row->platform ?? null;
            $data['device'] = $row->device ?? null;
            $data['language'] = $row->language ?? null;
            $data['country'] = isset(config('countries')[$row->country]) ? $row->country.':'.config('countries')[$row->country] : null;
            $data['city'] = isset(config('countries')[$row->country]) ? $row->country.':' : ':';
            $data['clicks'] = $date = substr($row->created_at, 0, 10);
            $data['clicks_hours'] = substr($row->created_at, 11, 2);

            foreach ($data as $name => $value) {
                $values[] = "({$row->link_id}, '{$name}', " . DB::connection()->getPdo()->quote(mb_substr($value, 0, 255)) . ", '{$date}')";
            }

            $values = implode(', ', $values);

            DB::statement("INSERT INTO `stats` (`link_id`, `name`, `value`, `date`) VALUES {$values} ON DUPLICATE KEY UPDATE `count` = `count` + 1;");
        }
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
