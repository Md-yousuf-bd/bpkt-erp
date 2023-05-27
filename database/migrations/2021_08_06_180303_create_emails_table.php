<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->longText('content')->nullable();
            $table->string('from');
            $table->string('to');
            $table->string('from_name');
            $table->string('to_name');
            $table->text('subject');
            $table->text('attached_file_links');
            $table->string('mail_type')->default('System Generated');
            $table->string('mail_purpose');
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
        Schema::dropIfExists('emails');
    }
}
