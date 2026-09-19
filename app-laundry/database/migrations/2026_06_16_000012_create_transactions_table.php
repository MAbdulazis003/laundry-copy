<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('total_weight', 8, 2)->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('status', ['received','weighed','processing','ready','picked_up','cancelled'])->default('received');
            $table->enum('payment_status', ['unpaid','partial','paid'])->default('unpaid');
            $table->timestamp('received_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
