<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function ($table) {
            $table->renameColumn('footer', 'visibility');
        });

        DB::table('settings')->insert(
            [
                ['name' => 'short_max_multi_links', 'value' => 10]
            ]
        );

        foreach (DB::table('plans')->select('*')->cursor() as $row) {
            $features = json_decode($row->features);

            $newKeys = [
                'api' => 'api',
                'links' => 'links',
                'spaces' => 'spaces',
                'domains' => 'domains',
                'pixels' => 'pixels',
                'stats' => 'link_stats',
                'targeting' => 'link_targeting',
                'expiration' => 'link_expiration',
                'password' => 'link_password',
                'disabled' => 'link_disabling',
                'utm' => 'link_utm',
                'global_domains' => 'global_domains',
                'deep_links' => 'link_deep',
                'data_export' => 'data_export'
            ];

            $newFeatures = array();
            foreach($features as $key => $value) {
                $newFeatures[$newKeys[$key]] = $value;
            }

            DB::statement("UPDATE `plans` SET `features` = :features WHERE `id` = :id", ['features' => json_encode($newFeatures), 'id' => $row->id]);
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
};
