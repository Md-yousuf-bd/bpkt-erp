<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('customer_name')->nullable();
            $table->text('remarks')->nullable();
            $table->string('ledger_head')->nullable();
            $table->date('date')->nullable();
            $table->double('debit')->nullable()->default(0.00);
            $table->double('credit')->nullable()->default(0.00);
            $table->string('voucher_no')->nullable();
            $table->string('ref_module')->nullable();
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
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
        Schema::dropIfExists('journals');
    }
}
