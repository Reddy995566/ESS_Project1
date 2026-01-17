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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('billing_first_name')->nullable()->after('session_id');
            $table->string('billing_last_name')->nullable()->after('billing_first_name');
            $table->string('billing_email')->nullable()->after('billing_last_name');
            $table->string('billing_phone')->nullable()->after('billing_email');
            $table->string('billing_country')->nullable()->after('billing_phone');
            $table->text('billing_address')->nullable()->after('billing_country');
            $table->text('billing_address_line_2')->nullable()->after('billing_address');
            $table->string('billing_city')->nullable()->after('billing_address_line_2');
            $table->string('billing_state')->nullable()->after('billing_city');
            $table->string('billing_zipcode')->nullable()->after('billing_state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'billing_first_name',
                'billing_last_name',
                'billing_email',
                'billing_phone',
                'billing_country',
                'billing_address',
                'billing_address_line_2',
                'billing_city',
                'billing_state',
                'billing_zipcode'
            ]);
        });
    }
};
