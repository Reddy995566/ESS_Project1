<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->enum('business_type', ['individual', 'company', 'partnership']);
            $table->string('business_registration_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('pan_number');
            $table->string('business_email');
            $table->string('business_phone');
            $table->text('business_address');
            $table->string('business_city');
            $table->string('business_state');
            $table->string('business_pincode');
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->text('description')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->enum('status', ['pending', 'approved', 'active', 'suspended', 'rejected'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->json('verification_documents')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
