<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('secret_key');
            $table->string('publish_key');
            $table->string('stripe_client_id', 200)->nullable();
            $table->string('commission');
            $table->string('fb_client_id');
            $table->string('fb_client_secret');
            $table->string('fb_redirect');
            $table->string('google_plus_client_id');
            $table->string('google_plus_secret');
            $table->string('google_plus_redirect');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_setting');
    }
}
