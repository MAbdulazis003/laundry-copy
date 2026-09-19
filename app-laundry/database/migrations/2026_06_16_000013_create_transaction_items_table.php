<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('restrict');
            $table->string('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_items');
    }
};
