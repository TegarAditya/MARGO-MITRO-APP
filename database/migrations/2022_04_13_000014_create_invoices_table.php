<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_suratjalan');
            $table->string('no_invoice');
            $table->date('date');
            $table->integer('nominal')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
