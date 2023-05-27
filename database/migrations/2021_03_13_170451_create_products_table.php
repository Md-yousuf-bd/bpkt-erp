<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable();
            $table->integer('vendor_id')->nullable()->default(0);
            $table->string('vendor_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('size')->nullable();
            $table->integer('unit_id');
            $table->integer('quantity')->default(0);
            $table->double('regular_price')->default(0.0);
            $table->double('discounted_price')->default(0.0);
            $table->double('rate_effective_date');
            $table->string('vds_section')->nullable();
            $table->double('vds_rate')->default(0.0);
            $table->string('tds_section')->nullable();
            $table->double('tds_rate')->default(0.0);
            $table->integer('is_best_sell')->nullable()->default(0);
            $table->integer('is_new')->nullable()->default(0);
            $table->string('tds_head')->nullable();
            $table->string('vds_head')->nullable();
            $table->string('assigned_ledger')->nullable();
            $table->double('opening_balance')->nullable()->default(0.0);
            $table->text('description')->nullable();
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
            $table->date('effective_date_from')->nullable();
            $table->date('effective_date_to')->nullable();
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
        Schema::dropIfExists('products');
    }
}
