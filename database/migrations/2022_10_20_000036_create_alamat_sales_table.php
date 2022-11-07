<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlamatSalesTable extends Migration
{
    public function up()
    {
        Schema::create('alamat_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('alamat')->nullable();
            $table->unsignedBigInteger('kota_sales_id')->nullable();
            $table->foreign('kota_sales_id', 'kota_sales_fk_7501459')->references('id')->on('kota_sales');
            $table->timestamps();
        });
    }
}
