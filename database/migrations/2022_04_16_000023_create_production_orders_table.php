<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_number')->nullable();
            $table->string('no_spk')->nullable();
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
