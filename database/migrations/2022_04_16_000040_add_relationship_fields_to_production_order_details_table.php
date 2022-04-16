<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToProductionOrderDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('production_order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('production_order_id')->nullable();
            $table->foreign('production_order_id', 'production_order_fk_6440975')->references('id')->on('production_orders');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_6440976')->references('id')->on('products');
        });
    }
}
