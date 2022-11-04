<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToHistoryProductionsTable extends Migration
{
    public function up()
    {
        Schema::table('history_productions', function (Blueprint $table) {
            $table->unsignedBigInteger('summary_order_id')->nullable();
            $table->foreign('summary_order_id', 'summary_order_fk_7550341')->references('id')->on('summary_orders');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_7550342')->references('id')->on('products');
        });
    }
}
