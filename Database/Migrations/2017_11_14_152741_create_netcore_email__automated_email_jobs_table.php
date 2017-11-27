<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcoreEmailAutomatedEmailJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_email__automated_email_jobs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('automated_email_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('other_user_id')->nullable();
            $table->timestamp('send_at')->nullable();
            $table->timestamps();

            $table->foreign('automated_email_id')->references('id')->on('netcore_email__automated_emails')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('other_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_email__automated_email_jobs');
    }
}
