<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('tax_type')->nullable();
            $table->string('account_head')->nullable();
            $table->string('section')->nullable();
            $table->string('lower_limit')->nullable()->default(0.0);
            $table->string('upper_limit')->nullable()->default(0.0);
            $table->string('compulsory_vds')->nullable();
            $table->string('basis')->nullable();
            $table->string('year')->nullable();
            $table->string('rate')->nullable()->default(0.0);
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
        Schema::dropIfExists('taxes');
    }
}
