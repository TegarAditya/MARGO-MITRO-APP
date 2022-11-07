<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToOrderPackagesTable extends Migration
{
    public function up()
    {
        Schema::table('order_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_7227575')->references('id')->on('products');
            $table->unsignedBigInteger('order_detail_id')->nullable();
            $table->foreign('order_detail_id', 'order_detail_fk_7227576')->references('id')->on('order_details')->onDelete('cascade');
        });
    }
}
