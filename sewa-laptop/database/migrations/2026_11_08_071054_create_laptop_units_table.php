<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laptop_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained()->onDelete('cascade');
            $table->string('kode_unit');
            $table->enum('status', ['ready', 'maintenance', 'not_ready', 'rented'])->default('ready');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laptop_units');
    }
};
