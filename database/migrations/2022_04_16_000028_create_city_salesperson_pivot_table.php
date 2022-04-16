<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitySalespersonPivotTable extends Migration
{
    public function up()
    {
        Schema::create('city_salesperson', function (Blueprint $table) {
            $table->unsignedBigInteger('salesperson_id');
            $table->foreign('salesperson_id', 'salesperson_id_fk_6430568')->references('id')->on('salespeople')->onDelete('cascade');
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id', 'city_id_fk_6430568')->references('id')->on('cities')->onDelete('cascade');
        });
    }
}
