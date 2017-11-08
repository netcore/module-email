<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcoreEmailCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_email__campaigns', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('last_user_id')->nullable();
            $table->enum('status', ['not_sent', 'sending', 'sent', 'stopped', 'error'])->default('not_sent');

            $table->timestamps();
        });

        Schema::create('netcore_email__campaign_translations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('netcore_email__campaigns')->onDelete('cascade');

            $table->string('locale')->index();
            $table->string('name');
            $table->longText('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_email__campaign_translations');
        Schema::dropIfExists('netcore_email__campaigns');
    }
}
