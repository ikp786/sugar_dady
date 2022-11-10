<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('social_media_id',100)->nullable();
            $table->string('full_name',50);
            $table->string('email_address',70)->unique();
            $table->string('mobile_number',10)->unique()->nullable();
            $table->longText('password')->nullable();
            $table->string('user_pic_name',50)->nullable();
            $table->unsignedBigInteger('gender_id');
            $table->unsignedBigInteger('sexcual_orientation_id');
            $table->Integer('intrested_ids');
            $table->string('user_location',255)->nullable();
            $table->longText('user_bio')->nullable();
            $table->longText('about_me')->nullable();
            $table->string('device_token',255)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('gender_id')->references('misc_id')->on('misc_mst');
            $table->foreign('sexcual_orientation_id')->references('misc_id')->on('misc_mst');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
