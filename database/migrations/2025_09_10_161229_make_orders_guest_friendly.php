<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeOrdersGuestFriendly extends Migration
{
    public function up()
    {
        // Drop current FK then make user_id nullable and re-add FK with set null
        Schema::table('orders', function (Blueprint $table) {
            // drop existing foreign key
            if (Schema::hasColumn('orders', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            // make user_id nullable (requires doctrine/dbal if column already exists)
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // re-add foreign key with onDelete set null
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // add guest/customer fields if not present
            if (! Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }
            if (! Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }
            if (! Schema::hasColumn('orders', 'shipping_address')) {
                $table->json('shipping_address')->nullable()->after('customer_phone');
            }
            if (! Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('shipping_status');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // drop added columns
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }
            if (Schema::hasColumn('orders', 'customer_phone')) {
                $table->dropColumn('customer_phone');
            }
            if (Schema::hasColumn('orders', 'customer_email')) {
                $table->dropColumn('customer_email');
            }
            if (Schema::hasColumn('orders', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }

            // revert user_id to not nullable and re-add cascade (only do this if you want)
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
