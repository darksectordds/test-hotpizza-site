<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_photo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url', 256);
            $table->unsignedInteger('product_id');
        });

        /**
        |-----------------------------------------------------------------------------
        | FOREIGN KEYS
        |----------------------------------------------------------------------
        |
         */

        Schema::table('product_photo', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('product');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_photo', function($table) {
            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('product_photo');
    }
}
