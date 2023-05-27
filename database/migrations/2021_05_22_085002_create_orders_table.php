<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('total_item');
            $table->integer('customer_address_id');
            $table->string('mobile');
            $table->string('alt_mobile')->nullable();
            $table->string('status');
            $table->string('payment_status');
            $table->double('payment_amount')->default(0.0);
            $table->string('payment_type');
            $table->string('payment_method');
            $table->double('grand_total')->default(0.0);
            $table->text('admin_comment')->nullable();
            $table->integer('status_changed_by')->nullable();
            $table->dateTime('status_changed_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
