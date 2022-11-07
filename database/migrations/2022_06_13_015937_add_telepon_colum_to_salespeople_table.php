<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeleponColumToSalespeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salespeople', function (Blueprint $table) {
            $table->string('telephone')->nullable()->after('name');
            $table->longText('alamat')->nullable()->after('telephone');
            $table->string('company')->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salespeople', function (Blueprint $table) {
            $table->dropColumn('telephone');
            $table->dropColumn('alamat');
            $table->dropColumn('company');
        });
    }
}
