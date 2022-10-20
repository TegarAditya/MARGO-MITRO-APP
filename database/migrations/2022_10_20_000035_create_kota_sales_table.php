<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKotaSalesTable extends Migration
{
    public function up()
    {
        Schema::create('kota_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->foreign('sales_id', 'sales_fk_7501442')->references('id')->on('salespeople');
            $table->unsignedBigInteger('kota_id')->nullable();
            $table->foreign('kota_id', 'kota_fk_7501443')->references('id')->on('cities');
            $table->timestamps();
        });
    }
}
