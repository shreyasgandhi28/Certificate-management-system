<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'email_sent_at')) {
                $table->timestamp('email_sent_at')->nullable()->after('generated_at');
            }
            if (!Schema::hasColumn('certificates', 'whatsapp_sent_at')) {
                $table->timestamp('whatsapp_sent_at')->nullable()->after('email_sent_at');
            }
            if (!Schema::hasColumn('certificates', 'send_attempts')) {
                $table->unsignedTinyInteger('send_attempts')->default(0)->after('status');
            }
            if (!Schema::hasColumn('certificates', 'last_error')) {
                $table->text('last_error')->nullable()->after('send_attempts');
            }
            if (!Schema::hasColumn('certificates', 'last_attempt_at')) {
                $table->timestamp('last_attempt_at')->nullable()->after('last_error');
            }
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (Schema::hasColumn('certificates', 'email_sent_at')) {
                $table->dropColumn('email_sent_at');
            }
            if (Schema::hasColumn('certificates', 'whatsapp_sent_at')) {
                $table->dropColumn('whatsapp_sent_at');
            }
            if (Schema::hasColumn('certificates', 'send_attempts')) {
                $table->dropColumn('send_attempts');
            }
            if (Schema::hasColumn('certificates', 'last_error')) {
                $table->dropColumn('last_error');
            }
            if (Schema::hasColumn('certificates', 'last_attempt_at')) {
                $table->dropColumn('last_attempt_at');
            }
        });
    }
};


