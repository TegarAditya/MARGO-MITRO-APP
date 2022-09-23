<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('price_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('diskon', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
