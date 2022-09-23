<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_adjustment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
