<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('operations_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('stage', ['received','washing','drying','ironing','finished']);
            $table->timestamp('performed_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('operations_logs');
    }
};
