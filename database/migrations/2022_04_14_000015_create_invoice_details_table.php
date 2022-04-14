<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity');
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('total', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
