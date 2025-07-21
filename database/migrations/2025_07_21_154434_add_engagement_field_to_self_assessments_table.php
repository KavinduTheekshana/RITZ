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
        Schema::table('self_assessments', function (Blueprint $table) {
            $table->boolean('engagement')->default(false)->after('self_assessment_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('self_assessments', function (Blueprint $table) {
            $table->dropColumn('engagement');
        });
    }
};