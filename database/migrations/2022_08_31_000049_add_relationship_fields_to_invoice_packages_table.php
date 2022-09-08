<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToInvoicePackagesTable extends Migration
{
    public function up()
    {
        Schema::table('invoice_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_detail_id')->nullable();
            $table->foreign('invoice_detail_id', 'invoice_detail_fk_7235705')->references('id')->on('invoice_details')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_7235706')->references('id')->on('products');
        });
    }
}
