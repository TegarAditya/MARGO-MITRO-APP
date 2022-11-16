<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePreorderDetailsPreorderDetailIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('deleted_at');

            $table->foreign('created_by')->references('id')->on('users');
        });

        Schema::table('preorder_details', function (Blueprint $table) {
            // $table->renameColumn('preorder_detail_id', 'preorder_id');

            $table->integer('group')->default(0)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->removeColumn('created_by');
        });

        Schema::table('preorder_details', function (Blueprint $table) {
            // $table->renameColumn('preorder_id', 'preorder_detail_id');

            $table->removeColumn('group');
        });
    }
}
