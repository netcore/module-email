<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcoreEmailCampaignUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_email__campaign_user', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('email_campaign_id');
            $table->unsignedInteger('user_id');
            $table->enum('is_sent', [0, 1])->default(0);

            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('email_campaign_id')->references('id')->on('netcore_email__campaigns')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_email__campaign_users');
    }
}
