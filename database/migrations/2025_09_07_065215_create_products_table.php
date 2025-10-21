<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            
            // 1. Individual foreign key indexes (CRUCIAL)
            $table->index('user_id');
            $table->index('category_id');
            
            // 2. Individual scalar indexes
            $table->index('price');
            $table->index('status');
            
            // 3. MOST IMPORTANT: Composite index for the most common query
            $table->index(['category_id', 'status', 'price']);

            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
