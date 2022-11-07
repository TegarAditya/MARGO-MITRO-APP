<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProductionOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('production_order_id')->nullable();
            $table->unsignedBigInteger('productionperson_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->double('order_qty')->default(0);
            $table->double('prod_qty')->default(0);
            $table->double('ongkos_satuan')->default(0);
            $table->double('ongkos_total')->default(0);
            $table->tinyInteger('is_ready')->default(0);
            $table->tinyInteger('is_check')->default(0);
            $table->integer('group')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('production_order_id')->references('id')->on('production_orders');
            $table->foreign('productionperson_id')->references('id')->on('productionpeople');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_order_details');
    }
}
