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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bio_location_id')->constrained();

            $table->string('location_name')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('emp_id');
            $table->string('type')->nullable();
            $table->string('logs');
            $table->string('status')->nullable();
            $table->string('api_checker')->nullable();
            $table->timestamps();

            // ✅ Composite unique index
            $table->unique(['emp_id', 'logs'], 'attendances_emp_logs_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
