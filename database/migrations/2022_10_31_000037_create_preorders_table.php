<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreordersTable extends Migration
{
    public function up()
    {
        Schema::create('preorders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_preorder');
            $table->date('date');
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
