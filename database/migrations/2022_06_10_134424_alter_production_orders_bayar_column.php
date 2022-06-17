<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductionOrdersBayarColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            DB::statement('ALTER TABLE `production_orders` MODIFY `bayar` VARCHAR(20) DEFAULT NULL');
            $table->renameColumn('bayar', 'type');
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
            DB::statement('ALTER TABLE `production_orders` MODIFY `type` DOUBLE DEFAULT 0');
            $table->renameColumn('type', 'bayar');
        });
    }
}
