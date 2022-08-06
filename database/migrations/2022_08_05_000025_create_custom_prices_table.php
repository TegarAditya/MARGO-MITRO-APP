<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomPricesTable extends Migration
{
    public function up()
    {
        Schema::create('custom_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->decimal('harga', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
