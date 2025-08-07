<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            if (!Schema::hasColumn('applicants', 'verification_started_at')) {
                $table->timestamp('verification_started_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('applicants', 'verification_started_by')) {
                $table->foreignId('verification_started_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('applicants', 'verification_completed_at')) {
                $table->timestamp('verification_completed_at')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'verification_completed_by')) {
                $table->foreignId('verification_completed_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('applicants', 'verification_notes')) {
                $table->text('verification_notes')->nullable();
            }

            if (!Schema::hasColumn('applicants', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }
            if (!Schema::hasColumn('applicants', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('applicants', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            if (Schema::hasColumn('applicants', 'verification_started_at')) {
                $table->dropColumn('verification_started_at');
            }
            if (Schema::hasColumn('applicants', 'verification_completed_at')) {
                $table->dropColumn('verification_completed_at');
            }
            if (Schema::hasColumn('applicants', 'verification_notes')) {
                $table->dropColumn('verification_notes');
            }
            if (Schema::hasColumn('applicants', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
            if (Schema::hasColumn('applicants', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('applicants', 'verification_started_by')) {
                $table->dropConstrainedForeignId('verification_started_by');
            }
            if (Schema::hasColumn('applicants', 'verification_completed_by')) {
                $table->dropConstrainedForeignId('verification_completed_by');
            }
            if (Schema::hasColumn('applicants', 'rejected_by')) {
                $table->dropConstrainedForeignId('rejected_by');
            }
        });
    }
};


