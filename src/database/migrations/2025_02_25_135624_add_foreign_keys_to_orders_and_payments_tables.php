<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrdersAndPaymentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders_and_payments_tables', function (Blueprint $table) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('payment_id')->references('payment_id')->on('payments')->onDelete('set null');
            });

            Schema::table('payments', function (Blueprint $table) {
                $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('set null');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders_and_payments_tables', function (Blueprint $table) {
            //
        });
    }
}
