<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSaldos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode');
            $table->string('periode');
            $table->unsignedBigInteger('salesperson_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->bigInteger('saldo_awal');
            $table->bigInteger('saldo_akhir');
            $table->bigInteger('tagihan');
            $table->bigInteger('retur');
            $table->bigInteger('bayar');
            $table->bigInteger('diskon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saldos', function (Blueprint $table) {
            Schema::dropIfExists('purchases');
        });
    }
}
