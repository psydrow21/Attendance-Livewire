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
        Schema::create('biometrics_models', function (Blueprint $table) {
            $table->id();
            $table->string('biometrics_model')->nullable();
            $table->string('status')->nullalbe('true')->comment('0 for Inactive and 1 for Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biometrics_models');
    }
};
