<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToStockAdjustmentDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('stock_adjustment_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_7317007')->references('id')->on('products');
            $table->unsignedBigInteger('stock_adjustment_id')->nullable();
            $table->foreign('stock_adjustment_id', 'stock_adjustment_fk_7317009')->references('id')->on('stock_adjustments');
        });
    }
}
