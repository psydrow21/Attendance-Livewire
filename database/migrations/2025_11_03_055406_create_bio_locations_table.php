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
        Schema::create('bio_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');

            $table->string('location')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('ip')->nullable();
            $table->string('ttl_option')->nullable()->comment('This option is selected through table of ttl_options_table');
            $table->string('biometrics_model')->nullable()->comment('This option is selected through table of biometrics_model');
            $table->string('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bio_locations');
    }
};
