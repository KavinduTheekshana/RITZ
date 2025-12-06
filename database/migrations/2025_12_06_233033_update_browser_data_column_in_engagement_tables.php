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
        // Update engagement_letter_companies table
        Schema::table('engagement_letter_companies', function (Blueprint $table) {
            $table->text('browser_data')->nullable()->change();
        });

        // Update engagement_letter_self_assessments table
        Schema::table('engagement_letter_self_assessments', function (Blueprint $table) {
            $table->text('browser_data')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert engagement_letter_companies table
        Schema::table('engagement_letter_companies', function (Blueprint $table) {
            $table->string('browser_data', 255)->nullable()->change();
        });

        // Revert engagement_letter_self_assessments table
        Schema::table('engagement_letter_self_assessments', function (Blueprint $table) {
            $table->string('browser_data', 255)->nullable()->change();
        });
    }
};
