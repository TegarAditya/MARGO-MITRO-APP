<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToProductionOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('productionperson_id')->nullable();
            $table->foreign('productionperson_id', 'productionperson_fk_6440940')->references('id')->on('productionpeople');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_6440936')->references('id')->on('users');
        });
    }
}
