<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('salesperson_id')->nullable();
            $table->foreign('salesperson_id', 'salesperson_fk_6415566')->references('id')->on('salespeople');
        });
    }
}
