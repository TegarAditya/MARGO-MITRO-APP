<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductionOrdersStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('0: Pending | 1: Checking | 2: Checked')->after('total');
            $table->text('note')->nullable()->after('status');
        });

        Schema::table('production_order_details', function (Blueprint $table) {
            $table->tinyInteger('file')->default(0)->after('is_check');
            $table->tinyInteger('plate')->default(0)->after('file');
            $table->tinyInteger('plate_ambil')->default(0)->after('plate');
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
            $table->dropColumn(['status', 'note']);
        });

        Schema::table('production_order_details', function (Blueprint $table) {
            $table->dropColumn(['file', 'plate', 'plate_ambil']);
        });
    }
}
