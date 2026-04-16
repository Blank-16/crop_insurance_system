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
        Schema::create('insurance_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposer_id')->constrained('users')->cascadeOnDelete();
            $table->string('crop_type');
            $table->string('region');
            $table->decimal('premium', 10, 2);
            $table->decimal('coverage', 10, 2);
            $table->integer('duration'); // duration in months
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_plans');
    }
};
