<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_user_id');            
            $table->foreign('from_user_id')->references('user_id')->on('users');
            $table->unsignedBigInteger('to_user_id');            
            $table->foreign('to_user_id')->references('user_id')->on('users');
            $table->string('message');
            $table->string('deep_link');
            $table->enum('is_sent',[0,1])->default(0);
            $table->enum('is_seen',[0,1])->default(0);
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
        Schema::dropIfExists('notifications');
    }
}
