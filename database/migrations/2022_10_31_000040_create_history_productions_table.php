<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryProductionsTable extends Migration
{
    public function up()
    {
        Schema::create('history_productions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('quantity');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
