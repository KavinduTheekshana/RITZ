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
        // Clients
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('preferred_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('deceased')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->boolean('password_mail')->default(false);
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('postal_address')->nullable();
            $table->text('previous_address')->nullable();
            $table->string('telephone_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('ni_number')->nullable();
            $table->string('personal_utr_number')->nullable();
            $table->date('terms_signed')->nullable();
            $table->boolean('photo_id_verified')->default(false);
            $table->boolean('address_verified')->default(false);
            $table->string('marital_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('preferred_language')->nullable();
            $table->boolean('create_self_assessment_client')->default(false);
            $table->boolean('client_does_their_own_sa')->default(false);
            $table->timestamps();
        });

        // Company details
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_number')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_status')->nullable();
            $table->enum('company_type', ['Private Limited Company', 'Sole Trader', 'Partnership', 'LLP', 'Other']);
            $table->date('incorporation_date')->nullable();
            $table->string('company_trading_as')->nullable();
            $table->text('registered_address')->nullable();
            $table->text('company_postal_address')->nullable();
            $table->string('invoice_address_type')->default('Registered Address');
            $table->string('company_email')->nullable();
            $table->string('company_email_domain')->nullable();
            $table->string('company_telephone')->nullable();
            $table->decimal('turnover', 15, 2)->nullable();
            $table->date('date_of_trading')->nullable();
            $table->string('sic_code')->nullable();
            $table->string('nature_of_business')->nullable();
            $table->string('corporation_tax_office')->nullable();
            $table->string('company_utr')->nullable();
            $table->string('companies_house_authentication_code')->nullable();
            $table->boolean('engagement')->default(false);
            $table->timestamps();
        });

        // Create a pivot table
        Schema::create('client_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Internal details
        Schema::create('company_internal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('internal_reference')->nullable();
            $table->string('allocated_office')->nullable();
            $table->string('client_grade')->nullable();
            $table->string('client_risk_level')->nullable();
            $table->text('notes')->nullable();
            $table->text('urgent')->nullable();
            $table->timestamps();
        });

        // Income details
        Schema::create('income_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->text('previous')->nullable();
            $table->text('current')->nullable();
            $table->text('ir_35_notes')->nullable();
            $table->timestamps();
        });


        // Previous accountant
        Schema::create('previous_accountant_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->boolean('clearance_required')->default(false);
            $table->string('accountant_email_address')->nullable();
            $table->text('accountant_details')->nullable();
            $table->date('send_first_clearance_email')->nullable();
            $table->string('automatically_request_every')->nullable();
            $table->date('last_requested')->nullable();
            $table->boolean('information_received')->default(false);
            $table->timestamps();
        });

        // Services
        Schema::create('services_requireds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->decimal('accounts', 15, 2)->nullable();
            $table->decimal('bookkeeping', 15, 2)->nullable();
            $table->decimal('ct600_return', 15, 2)->nullable();
            $table->decimal('payroll', 15, 2)->nullable();
            $table->decimal('auto_enrolment', 15, 2)->nullable();
            $table->decimal('vat_returns', 15, 2)->nullable();
            $table->decimal('management_accounts', 15, 2)->nullable();
            $table->decimal('confirmation_statement', 15, 2)->nullable();
            $table->decimal('cis', 15, 2)->nullable();
            $table->decimal('p11d', 15, 2)->nullable();
            $table->decimal('fee_protection_service', 15, 2)->nullable();
            $table->decimal('registered_address', 15, 2)->nullable();
            $table->decimal('bill_payment', 15, 2)->nullable();
            $table->decimal('consultation_advice', 15, 2)->nullable();
            $table->decimal('software', 15, 2)->nullable();
            $table->decimal('annual_charge', 15, 2)->nullable();
            $table->decimal('monthly_charge', 15, 2)->nullable();
            $table->timestamps();
        });

        // Accounts and returns details
        Schema::create('accounts_and_returns_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('accounts_period_end')->nullable();
            $table->date('ch_year_end')->nullable();
            $table->date('hmrc_year_end')->nullable();
            $table->date('ch_accounts_next_due')->nullable();
            $table->date('ct600_due')->nullable();
            $table->decimal('corporation_tax_amount_due', 15, 2)->nullable();
            $table->date('tax_due_hmrc_year_end')->nullable();
            $table->string('ct_payment_reference')->nullable();
            $table->string('tax_office')->nullable();
            $table->boolean('companies_house_email_reminder')->default(false);
            $table->string('accounts_latest_action')->nullable();
            $table->date('accounts_latest_action_date')->nullable();
            $table->date('accounts_records_received')->nullable();
            $table->text('accounts_progress_note')->nullable();
            $table->timestamps();
        });

        // Confirmation statement
        Schema::create('confirmation_statement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('confirmation_statement_date')->nullable();
            $table->date('confirmation_statement_due')->nullable();
            $table->string('latest_action')->nullable();
            $table->date('latest_action_date')->nullable();
            $table->date('records_received')->nullable();
            $table->text('progress_note')->nullable();
            $table->text('officers')->nullable();
            $table->text('share_capital')->nullable();
            $table->text('shareholders')->nullable();
            $table->text('people_with_significant_control')->nullable();
            $table->timestamps();
        });

        // VAT details
        Schema::create('vat_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('vat_frequency')->nullable();
            $table->date('vat_period_end')->nullable();
            $table->date('next_return_due')->nullable();
            $table->decimal('vat_bill_amount', 15, 2)->nullable();
            $table->date('vat_bill_due')->nullable();
            $table->string('latest_action')->nullable();
            $table->date('latest_action_date')->nullable();
            $table->date('records_received')->nullable();
            $table->text('progress_note')->nullable();
            $table->string('vat_member_state')->nullable();
            $table->string('vat_number')->nullable();
            $table->text('vat_address')->nullable();
            $table->date('date_of_registration')->nullable();
            $table->date('effective_date')->nullable();
            $table->decimal('estimated_turnover', 15, 2)->nullable();
            $table->date('applied_for_mtd')->nullable();
            $table->boolean('mtd_ready')->default(false);
            $table->boolean('transfer_of_going_concern')->default(false);
            $table->boolean('involved_in_other_businesses')->default(false);
            $table->boolean('direct_debit')->default(false);
            $table->boolean('standard_scheme')->default(false);
            $table->boolean('cash_accounting_scheme')->default(false);
            $table->boolean('retail_scheme')->default(false);
            $table->boolean('margin_scheme')->default(false);
            $table->boolean('flat_rate')->default(false);
            $table->string('flat_rate_category')->nullable();
            $table->string('month_of_last_quarter_submitted')->nullable();
            $table->string('box_5_of_last_quarter_submitted')->nullable();
            $table->text('general_notes')->nullable();
            $table->timestamps();
        });

        // PAYE details
        Schema::create('paye_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('employers_reference')->nullable();
            $table->string('accounts_office_reference')->nullable();
            $table->string('years_required')->nullable();
            $table->string('paye_frequency')->nullable();
            $table->boolean('irregular_monthly_pay')->default(false);
            $table->boolean('nil_eps')->default(false);
            $table->integer('number_of_employees')->nullable();
            $table->text('salary_details')->nullable();
            $table->date('first_pay_date')->nullable();
            $table->date('rti_deadline')->nullable();
            $table->date('paye_scheme_ceased')->nullable();
            $table->string('paye_latest_action')->nullable();
            $table->date('paye_latest_action_date')->nullable();
            $table->date('paye_records_received')->nullable();
            $table->text('paye_progress_note')->nullable();
            $table->text('general_notes')->nullable();
            $table->timestamps();
        });

        // Auto-Enrolment details
        Schema::create('auto_enrolment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('latest_action')->nullable();
            $table->date('latest_action_date')->nullable();
            $table->date('records_received')->nullable();
            $table->text('progress_note')->nullable();
            $table->date('staging')->nullable();
            $table->date('postponement_date')->nullable();
            $table->date('tpr_opt_out_date')->nullable();
            $table->date('re_enrolment_date')->nullable();
            $table->string('pension_provider')->nullable();
            $table->string('pension_id')->nullable();
            $table->date('declaration_of_compliance_due')->nullable();
            $table->date('declaration_of_compliance_submission')->nullable();
            $table->timestamps();
        });

        // P11D details
        Schema::create('p11d_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('next_p11d_return_due')->nullable();
            $table->date('latest_p11d_submitted')->nullable();
            $table->string('latest_action')->nullable();
            $table->date('latest_action_date')->nullable();
            $table->date('records_received')->nullable();
            $table->text('progress_note')->nullable();
            $table->timestamps();
        });


        // Registration
        Schema::create('registration_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->boolean('terms_signed_registration_fee_paid')->default(false);
            $table->decimal('fee', 15, 2)->nullable();
            $table->date('letter_of_engagement_signed')->nullable();
            $table->boolean('money_laundering_complete')->default(false);
            $table->date('registration_64_8')->nullable();
            $table->timestamps();
        });


        // Other details
        Schema::create('other_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('referred_by')->nullable();
            $table->date('initial_contact')->nullable();
            $table->date('proposal_email_sent')->nullable();
            $table->date('welcome_email')->nullable();
            $table->string('accounting_system')->nullable();
            $table->string('profession')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter_handle')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_handle')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('client_company');
        Schema::dropIfExists('company_internal_details');
        Schema::dropIfExists('income_details');
        Schema::dropIfExists('previous_accountant_details');
        Schema::dropIfExists('services_required');
        Schema::dropIfExists('accounts_and_returns_details');
        Schema::dropIfExists('confirmation_statement_details');
        Schema::dropIfExists('vat_details');
        Schema::dropIfExists('paye_details');
        Schema::dropIfExists('auto_enrolment_details');
        Schema::dropIfExists('p11d_details');
        Schema::dropIfExists('registration_details');
        Schema::dropIfExists('other_details');
    }
};
