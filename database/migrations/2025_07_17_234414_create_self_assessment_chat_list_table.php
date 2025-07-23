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
        Schema::create('self_assessment_chat_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('self_assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Admin user
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null'); // Client
            $table->enum('sender_type', ['admin', 'client', 'system']);
            $table->string('sender_name');
            $table->string('sender_email');
            $table->text('message')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->nullable();
            
            // Fields for signature requirements
            $table->boolean('requires_signature')->default(false);
            $table->boolean('is_signed')->default(false);
            $table->string('signer_full_name')->nullable();
            $table->string('signer_print_name')->nullable();
            $table->string('signer_email')->nullable();
            $table->string('signer_ip')->nullable();
            $table->text('signer_browser_data')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('signed_file_path')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_assessment_chat_lists');
    }
};