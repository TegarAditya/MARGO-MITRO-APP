<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('tagihan_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->string('type')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
