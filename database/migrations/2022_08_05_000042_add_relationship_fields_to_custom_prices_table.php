<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCustomPricesTable extends Migration
{
    public function up()
    {
        Schema::table('custom_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->foreign('kategori_id', 'kategori_fk_7108424')->references('id')->on('categories');
        });
    }
}
