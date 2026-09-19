<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL, we need to alter the enum
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('received','weighed','processing','washing','drying','ironing','ready','picked_up','cancelled') DEFAULT 'received'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('received','weighed','processing','ready','picked_up','cancelled') DEFAULT 'received'");
    }
};
