<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcoreEmailAutomatedEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_email__automated_emails', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('last_user_id')->nullable();
            $table->string('key');
            $table->string('period')->nullable();
            $table->string('type');
            $table->boolean('is_active')->default(0);

            $table->timestamp('last_sent_at')->nullable();
        });

        Schema::create('netcore_email__automated_email_translations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('automated_email_id');
            $table->foreign('automated_email_id', 'automated_email_translations_automated_email_id_foreign')->references('id')->on('netcore_email__automated_emails')->onDelete('cascade');

            $table->string('locale')->index();
            $table->string('name');
            $table->mediumText('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_email__automated_email_translations');
        Schema::dropIfExists('netcore_email__automated_emails');
    }
}
