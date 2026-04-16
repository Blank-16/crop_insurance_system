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
        // 1. Update Claims Table
        Schema::table('claims', function (Blueprint $table) {
            $table->string('damage_type')->nullable(); // flood, drought, pest, storm
            $table->integer('damage_percentage')->nullable(); // 0-100
            $table->decimal('calculated_amount', 12, 2)->nullable();
            $table->text('remarks')->nullable(); // rejection reasons or general notes
        });

        // 2. Update Policies Table
        Schema::table('policies', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });

        // 3. Create Audit Logs Table
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // "approved claim", "created plan"
            $table->string('target_id')->nullable(); // Contextual ID (can be string or int)
            $table->timestamp('created_at')->useCurrent();
        });

        // 4. Create Notifications Table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 5. Create Claim Logs Table (Timeline)
        Schema::create('claim_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claims')->cascadeOnDelete();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('audit_logs');
        
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });

        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['damage_type', 'damage_percentage', 'calculated_amount', 'remarks']);
        });
    }
};
