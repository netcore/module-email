<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcoreEmailAutomatedEmailJobVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_email__automated_email_job_variables', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('automated_email_job_id');

            $table->string('key');
            $table->string('value');


            $table->foreign('automated_email_job_id', 'automated_email_job_variables_automated_email_jobs_foreign')
                  ->references('id')
                  ->on('netcore_email__automated_email_jobs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_email__automated_email_job_variables');
    }
}
