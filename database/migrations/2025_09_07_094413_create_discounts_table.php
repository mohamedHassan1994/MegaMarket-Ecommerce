<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // e.g. "Summer Sale", "Black Friday"
        $table->enum('type', ['percentage', 'fixed']); // discount type
        $table->decimal('value', 10, 2); // e.g. 10% or $50
        $table->boolean('is_exclusive')->default(false); // whether it excludes other discounts
        $table->boolean('is_coupon')->default(false); // coupon-based or automatic
        $table->string('code')->nullable(); // only filled if coupon
        $table->timestamp('starts_at')->nullable();
        $table->timestamp('ends_at')->nullable();
        $table->boolean('active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
