<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_product', function (Blueprint $table) {
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('product_id');
        });

        /**
        |-----------------------------------------------------------------------------
        | FOREIGN KEYS
        |----------------------------------------------------------------------
        |
         */

        Schema::table('cart_product', function (Blueprint $table) {
            $table->foreign('cart_id')->references('id')->on('cart');
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
        Schema::table('cart_product', function($table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['cart_id']);
        });

        Schema::dropIfExists('cart_product');
    }
}
