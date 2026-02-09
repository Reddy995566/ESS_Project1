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
        Schema::create('seller_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->enum('verification_type', [
                'identity',
                'business',
                'bank_account',
                'address',
                'phone',
                'email'
            ]);
            $table->enum('status', ['pending', 'in_progress', 'approved', 'rejected'])->default('pending');
            $table->json('verification_data')->nullable(); // Store verification details
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // For time-sensitive verifications
            $table->timestamps();
            
            $table->unique(['seller_id', 'verification_type']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_verifications');
    }
};
