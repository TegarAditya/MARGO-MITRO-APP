<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePackagesTable extends Migration
{
    public function up()
    {
        Schema::create('invoice_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
