<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('shop_no');
            $table->string('vendor_name');
            $table->string('owner_name')->nullable();
            $table->string('owner_contact')->nullable();
            $table->string('owner_nid')->nullable();
            $table->string('email')->nullable();
            $table->string('etin')->nullable();
            $table->string('owner_address')->nullable();
            $table->string('trade_lincese_no')->nullable();
            $table->string('incorporation_no')->nullable();
            $table->string('bin')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('supplier_type')->nullable();
            $table->string('payment_method')->nullable();
            $table->integer('region')->nullable();
            $table->string('bank_account_title')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('credit_period')->nullable();
            $table->tinyInteger('status');
            $table->integer('created_by');
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
        Schema::dropIfExists('vendors');
    }
}
