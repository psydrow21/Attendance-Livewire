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
        Schema::create('ttl_options', function (Blueprint $table) {
            $table->id();
            $table->string('ttl_respond')->nullable('true');
            $table->string('system_action')->nullable('true');
            $table->string('status')->nullalbe('true')->comment('0 for Inactive and 1 for Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ttl_options');
    }
};
