<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductionOrdersProductionpersonColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('productionperson_id')->nullable()->after('finishing_order_id');
            $table->string('type')->nullable()->after('no_order');
            $table->double('total')->default(0)->after('date');

            $table->foreign('productionperson_id')->references('id')->on('productionpeople');
        });

        Schema::table('production_order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('finishing_order_id')->nullable()->after('productionperson_id');

            $table->foreign('finishing_order_id')->references('id')->on('finishing_orders');
        });

        Schema::table('realisasis', function (Blueprint $table) {
            $table->unsignedBigInteger('production_order_id')->nullable()->after('finishing_order_id');

            $table->foreign('production_order_id')->references('id')->on('production_orders');
        });

        Schema::table('realisasi_details', function (Blueprint $table) {
            $table->unsignedBigInteger('production_order_id')->nullable()->after('fo_detail_id');
            $table->unsignedBigInteger('po_detail_id')->nullable()->after('production_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->dropForeign(['productionperson_id']);
            $table->dropColumn('productionperson_id');
            $table->dropColumn('total');
        });

        Schema::table('production_order_details', function (Blueprint $table) {
            $table->dropForeign(['finishing_order_id']);
            $table->dropColumn('finishing_order_id');
        });

        Schema::table('realisasis', function (Blueprint $table) {
            $table->dropForeign(['production_order_id']);
            $table->dropColumn('production_order_id');
        });

        Schema::table('realisasi_details', function (Blueprint $table) {
            $table->dropColumn('production_order_id');
            $table->dropColumn('po_detail_id');
        });
    }
}
