<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('payment_type');
            $table->string('payment_method');
            $table->string('payment_number')->nullable();
            $table->text('payment_reference')->nullable();
            $table->date('payment_date');
            $table->double('order_amount');
            $table->double('paid_amount')->default(0.0);
            $table->double('due_amount')->default(0.0);
            $table->integer('created_by');
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
        Schema::dropIfExists('payments');
    }
}
