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
        Schema::create('engagement_letter_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->longText('content');
            $table->string('file_path')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->string('sent_by')->nullable();
            $table->string('signer_full_name')->nullable();
            $table->string('signer_print_name')->nullable();
            $table->string('signer_email')->nullable();
            $table->string('ip')->nullable();
            $table->string('browser_data')->nullable();
            $table->date('signed_date')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagement_letter_companies');
    }
};
