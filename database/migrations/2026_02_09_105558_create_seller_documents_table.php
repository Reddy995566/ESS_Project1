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
        Schema::create('seller_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'pan_card',
                'gst_certificate', 
                'business_registration',
                'bank_statement',
                'address_proof',
                'identity_proof',
                'cancelled_cheque',
                'other'
            ]);
            $table->string('document_name');
            $table->string('file_path');
            $table->string('file_url')->nullable();
            $table->string('file_id')->nullable(); // For ImageKit or other CDN
            $table->integer('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->json('metadata')->nullable(); // For additional document info
            $table->timestamps();
            
            $table->index(['seller_id', 'document_type']);
            $table->index(['verification_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_documents');
    }
};
