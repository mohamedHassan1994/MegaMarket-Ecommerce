<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePaymentMethodInOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cod', 'online'])
                ->after('total_amount')
                ->default('cod');
        });
    }


    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rollback: drop enum and restore string
            $table->dropColumn('payment_method');
            $table->string('payment_method')->after('total_amount');
        });
    }
}
