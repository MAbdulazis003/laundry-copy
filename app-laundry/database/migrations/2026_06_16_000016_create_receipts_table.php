<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('printed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('printed_at')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('receipts');
    }
};
