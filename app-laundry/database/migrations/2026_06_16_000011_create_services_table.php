<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('pricing_type', ['per_kg', 'per_item'])->default('per_kg');
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('estimated_days')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
