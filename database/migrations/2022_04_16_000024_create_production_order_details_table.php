<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionOrderDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('production_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_qty');
            $table->integer('prod_qty');
            $table->decimal('ongkos_satuan', 15, 2);
            $table->decimal('ongkos_total', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
