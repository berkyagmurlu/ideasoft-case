<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // total_amount, category_quantity, category_multiple, user_revenue, membership_duration
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('min_amount', 10, 2)->nullable();
            $table->integer('min_quantity')->nullable();
            $table->decimal('discount_rate', 5, 2)->nullable();
            $table->integer('free_items')->nullable();
            $table->decimal('user_revenue_min', 10, 2)->nullable();
            $table->integer('membership_months_min')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
