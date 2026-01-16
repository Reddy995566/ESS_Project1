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
        Schema::table('fabrics', function (Blueprint $table) {
            if (Schema::hasColumn('fabrics', 'abbreviation')) {
                $table->dropColumn('abbreviation');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fabrics', function (Blueprint $table) {
            if (!Schema::hasColumn('fabrics', 'abbreviation')) {
                $table->string('abbreviation', 10)->nullable()->after('name');
            }
        });
    }
};
