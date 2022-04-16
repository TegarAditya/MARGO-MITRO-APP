<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionpeopleTable extends Migration
{
    public function up()
    {
        Schema::create('productionpeople', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('type');
            $table->string('contact')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
