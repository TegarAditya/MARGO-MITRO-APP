<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_kwitansi')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->decimal('diskon', 15, 2)->nullable();
            $table->integer('bayar');
            $table->date('tanggal');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
