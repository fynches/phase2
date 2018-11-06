<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_verify_code')->after('remember_token');
            $table->string('verify_forgot_password')->after('email_verify_code');
            $table->enum('provider', ['0', '1', '2'])->after('verify_forgot_password');
            $table->string('facebook_id')->after('provider');
            $table->string('google_id')->after('provider');
            $table->string('token')->after('google_id');
            $table->string('last_name')->after('name');
            $table->string('profile_image');
            $table->enum('user_status', ['Active', 'InActive']);
            $table->enum('user_type', ['1', '2' ,'3'])->after('email');
            $table->softDeletes()->after('updated_at');
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
