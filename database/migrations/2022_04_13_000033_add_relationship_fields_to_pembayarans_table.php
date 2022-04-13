<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPembayaransTable extends Migration
{
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->unsignedBigInteger('tagihan_id')->nullable();
            $table->foreign('tagihan_id', 'tagihan_fk_6416949')->references('id')->on('tagihans');
        });
    }
}
