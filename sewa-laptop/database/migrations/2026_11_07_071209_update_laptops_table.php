<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('laptops', function (Blueprint $table) {
            $table->decimal('harga_sewa_per_hari', 15, 2)->change();
            $table->enum('status', ['ready', 'maintenance', 'empty', 'not_ready'])->default('ready');
        });
    }

    public function down(): void {
        Schema::table('laptops', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
