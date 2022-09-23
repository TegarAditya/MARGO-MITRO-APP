<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPriceDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('price_details', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->foreign('sales_id', 'sales_fk_7364361')->references('id')->on('salespeople');
            $table->unsignedBigInteger('price_id')->nullable();
            $table->foreign('price_id', 'price_fk_7364378')->references('id')->on('prices');
        });
    }
}
