<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPackagesTable extends Migration
{
    public function up()
    {
        Schema::create('order_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity');
            $table->integer('moved')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
