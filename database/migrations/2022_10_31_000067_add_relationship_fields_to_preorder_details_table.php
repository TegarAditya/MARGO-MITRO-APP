<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPreorderDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('preorder_details', function (Blueprint $table) {
            $table->unsignedBigInteger('preorder_detail_id')->nullable();
            $table->foreign('preorder_detail_id', 'preorder_detail_fk_7549385')->references('id')->on('preorders');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_7549386')->references('id')->on('products');
        });
    }
}
