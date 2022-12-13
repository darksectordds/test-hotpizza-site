<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('id');
            $table->string('session_id');
            $table->unsignedInteger('customer_id')->nullable()->default(null);
        });

        /**
        |-----------------------------------------------------------------------------
        | FOREIGN KEYS
        |----------------------------------------------------------------------
        |
         */

        Schema::table('cart', function (Blueprint $table) {
            $table->foreign('session_id')->references('id')->on('sessions');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart', function($table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['session_id']);
        });

        Schema::dropIfExists('cart');
    }
}
