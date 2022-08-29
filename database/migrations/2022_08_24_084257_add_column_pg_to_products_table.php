<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPgToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('tipe_pg')->nullable();
            $table->unsignedBigInteger('pg_id')->nullable();
            $table->unsignedBigInteger('kunci_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('tipe_pg');
            $table->dropColumn('pg_id');
            $table->dropColumn('kunci_id');
        });
    }
}
