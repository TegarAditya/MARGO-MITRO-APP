<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProducionOrdersTableToFinishingOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('production_orders', 'finishing_orders');
        Schema::rename('production_order_details', 'finishing_order_details');

        Schema::table('finishing_order_details', function(Blueprint $table) {
            $table->renameColumn('production_order_id', 'finishing_order_id');
        });

        Schema::table('realisasis', function(Blueprint $table) {
            $table->renameColumn('production_order_id', 'finishing_order_id');
        });

        Schema::table('realisasi_details', function(Blueprint $table) {
            $table->renameColumn('production_order_id', 'finishing_order_id');
            $table->renameColumn('po_detail_id', 'fo_detail_id');
        });

        Schema::create('production_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('finishing_order_id')->nullable();
            $table->string('no_order')->nullable();
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('finishing_order_id')->references('id')->on('finishing_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_orders');

        Schema::table('realisasi_details', function(Blueprint $table) {
            $table->renameColumn('finishing_order_id', 'production_order_id');
            $table->renameColumn('fo_detail_id', 'po_detail_id');
        });

        Schema::table('realisasis', function(Blueprint $table) {
            $table->renameColumn('finishing_order_id', 'production_order_id');
        });

        Schema::table('finishing_order_details', function(Blueprint $table) {
            $table->renameColumn('finishing_order_id', 'production_order_id');
        });

        Schema::rename('finishing_orders', 'production_orders');
        Schema::rename('finishing_order_details', 'production_order_details');
    }
}
