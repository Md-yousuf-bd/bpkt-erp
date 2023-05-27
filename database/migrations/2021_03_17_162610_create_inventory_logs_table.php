<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('source_id');
            $table->integer('transaction_type_id');
            $table->integer('transaction_sub_type_id');
            $table->integer('unit_id');
            $table->double('quantity');
            $table->double('purchase_price');
            $table->double('sell_price');
            $table->double('total_purchase_price');
            $table->double('total_sell_price');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('inventory_logs');
    }
}
