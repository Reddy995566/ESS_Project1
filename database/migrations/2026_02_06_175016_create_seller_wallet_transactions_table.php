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
        Schema::create('seller_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained('seller_wallets')->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->enum('type', ['credit', 'debit']);
            $table->enum('category', ['order_commission', 'withdrawal', 'refund', 'bonus', 'penalty', 'adjustment']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('description');
            $table->json('metadata')->nullable(); // For storing additional data like order_id, payout_id, etc.
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->foreignId('payout_id')->nullable()->constrained('seller_payouts')->onDelete('set null');
            $table->string('reference_type')->nullable(); // Model type for polymorphic relation
            $table->unsignedBigInteger('reference_id')->nullable(); // Model ID for polymorphic relation
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index(['seller_id', 'type']);
            $table->index(['wallet_id', 'created_at']);
            $table->index(['transaction_id']);
            $table->index(['status']);
            $table->index(['category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_wallet_transactions');
    }
};
