<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('shop_no' )->nullable();
            $table->string('shop_name' )->nullable();
            $table->string('invoice_no' )->nullable();
            $table->string('person_id' )->nullable()->default(0);
            $table->double('vat' )->nullable()->default(0.00);
            $table->double('vat_amount' )->nullable()->default(0.00);
            $table->double('total' )->nullable()->default(0.00);
            $table->double('grand_total' )->nullable()->default(0.00);
            $table->integer('created_by' )->nullable()->default(0);
            $table->integer('updated_by' )->nullable()->default(0);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('incomes');
    }
}
