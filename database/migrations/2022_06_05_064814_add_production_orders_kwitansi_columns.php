<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductionOrdersKwitansiColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->string('no_kwitansi', 16)->nullable()->after('no_spk');
            $table->double('total')->default(0)->after('date');
            $table->double('bayar')->default(0)->after('total');
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
            $table->dropColumn([
                'no_kwitansi', 'total', 'bayar',
            ]);
        });
    }
}
