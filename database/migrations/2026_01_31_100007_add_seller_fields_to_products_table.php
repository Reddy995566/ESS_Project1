<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('seller_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->enum('approval_status', ['draft', 'pending', 'approved', 'rejected'])->default('approved')->after('status');
            $table->foreignId('approved_by')->nullable()->after('approval_status')->constrained('admins')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejection_reason')->nullable()->after('approved_at');
            
            $table->index('seller_id');
            $table->index('approval_status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['seller_id', 'approval_status', 'approved_by', 'approved_at', 'rejection_reason']);
        });
    }
};
