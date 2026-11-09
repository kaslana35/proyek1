<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained()->onDelete('cascade');
            $table->string('status_sebelum');
            $table->string('status_sesudah');
            $table->integer('jumlah');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_inspections');
    }
};
