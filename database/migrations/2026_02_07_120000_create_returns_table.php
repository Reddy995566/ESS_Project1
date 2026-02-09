<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('seller_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('amount', 10, 2);
            $table->enum('reason', [
                'defective',
                'wrong_item',
                'size_issue',
                'quality_issue',
                'not_as_described',
                'damaged_shipping',
                'changed_mind',
                'other'
            ]);
            $table->text('reason_details')->nullable();
            $table->json('images')->nullable(); // Return images
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'picked_up',
                'refunded',
                'cancelled'
            ])->default('pending');
            $table->enum('refund_method', ['original_payment', 'wallet', 'bank_transfer'])->default('original_payment');
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('seller_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('tracking_number')->nullable();
            $table->foreignId('processed_by_admin')->nullable()->constrained('admins')->onDelete('set null');
            $table->foreignId('processed_by_seller')->nullable()->constrained('sellers')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['order_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['seller_id', 'status']);
            $table->index('return_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};