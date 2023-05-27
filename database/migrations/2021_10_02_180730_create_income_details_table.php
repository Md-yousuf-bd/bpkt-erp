<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('income_id');
            $table->string('income_head');
            $table->integer('income_head_id');
            $table->foreign('income_id')->references('id')->on('incomes')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->double('amount')->nullable()->default(0.00);
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
        Schema::dropIfExists('income_details');
    }
}
