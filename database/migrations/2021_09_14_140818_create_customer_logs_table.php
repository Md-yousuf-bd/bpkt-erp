<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('shop_no');
            $table->string('shop_name');
            $table->string('owner_name');
            $table->string('owner_contact')->nullable();
            $table->string('owner_nid')->nullable();
            $table->string('email')->nullable();
            $table->string('owner_address')->nullable();
            $table->string('trade_lincese_no')->nullable();
            $table->string('incorporation_no')->nullable();
            $table->string('bin')->nullable();
            $table->string('etin')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('region')->nullable();
            $table->string('contact_no')->nullable();
            $table->date('contact_date')->nullable();
            $table->date('contact_s_date')->nullable();
            $table->date('renewal_date')->nullable();
            $table->date('contact_closure_date')->nullable();
            $table->double('advance_deposit')->nullable();
            $table->double('security_deposit')->nullable();
            $table->double('adj_adv_deposit')->nullable();
            $table->date('adj_effective_from')->nullable();
            $table->date('adj_closure_date')->nullable();
            $table->double('monthly_rent')->nullable();
            $table->double('renewal_rent')->nullable();
            $table->double('service_charge')->nullable();
            $table->string('billing_system')->nullable();
            $table->string('credit_period')->nullable();
            $table->integer('contact_owner_name')->nullable();
            $table->string('password_visible')->nullable();
            $table->tinyInteger('status');
            $table->string('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('customer_logs');
    }
}
