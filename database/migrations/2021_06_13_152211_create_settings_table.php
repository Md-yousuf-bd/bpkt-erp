<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
           $table->increments('id');
           $table->double('delivery_charge')->nullable();
           $table->text('copyright')->nullable();
           $table->text('small_about')->nullable();
           $table->text('company_address')->nullable();
           $table->text('company_email_address')->nullable();
           $table->text('company_contact_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
