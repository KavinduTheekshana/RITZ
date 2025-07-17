<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_chat_lists', function (Blueprint $table) {
            // Signature requirement fields
            $table->boolean('requires_signature')->default(false)->after('file_type');
            $table->boolean('is_signed')->default(false)->after('requires_signature');
            
            // Signature details
            $table->string('signer_full_name')->nullable()->after('is_signed');
            $table->string('signer_print_name')->nullable()->after('signer_full_name');
            $table->string('signer_email')->nullable()->after('signer_print_name');
            $table->string('signer_ip')->nullable()->after('signer_email');
            $table->text('signer_browser_data')->nullable()->after('signer_ip');
            $table->timestamp('signed_at')->nullable()->after('signer_browser_data');
            $table->string('signed_file_path')->nullable()->after('signed_at');
        });
    }

    public function down(): void
    {
        Schema::table('company_chat_lists', function (Blueprint $table) {
            $table->dropColumn([
                'requires_signature',
                'is_signed',
                'signer_full_name',
                'signer_print_name',
                'signer_email',
                'signer_ip',
                'signer_browser_data',
                'signed_at',
                'signed_file_path'
            ]);
        });
    }
};