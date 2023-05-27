<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSMSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_m_s', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->string('sender_id');
            $table->string('bulk_company');
            $table->string('phone_number');
            $table->double('cost_per_sms')->default(0);
            $table->double('sms_counted')->default(0);
            $table->double('total_cost')->default(0);
            $table->string('sms_type')->default('System Generated');
            $table->string('sms_purpose');
            $table->string('status');
            $table->string('status_text');
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
        Schema::dropIfExists('s_m_s');
    }
}
