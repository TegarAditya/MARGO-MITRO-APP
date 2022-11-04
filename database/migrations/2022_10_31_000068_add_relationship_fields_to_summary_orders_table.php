<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSummaryOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('summary_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('preorder_id')->nullable();
            $table->foreign('preorder_id', 'preorder_fk_7549513')->references('id')->on('preorders');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id', 'order_fk_7549514')->references('id')->on('orders');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_7549515')->references('id')->on('products');
        });
    }
}
