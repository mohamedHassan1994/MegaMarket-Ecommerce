<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_gateway'); // e.g., stripe, paypal, paymob, paytabs
            $table->string('payment_method_type'); // e.g.g, card, paypal, cash
            $table->decimal('amount', 10, 2);
            $table->enum('currency', ['EGP', 'EUR', 'USD'])->default('EGP');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded']);
            $table->string('gateway_transaction_id')->nullable(); // Store raw response from gateway
            
            $table->text('failure_message')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index('status');
            $table->index('gateway_transaction_id');
            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
