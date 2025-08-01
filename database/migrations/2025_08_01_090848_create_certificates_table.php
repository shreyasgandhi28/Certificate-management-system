<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->constrained('certificate_templates');
            $table->string('serial_number')->unique();
            $table->string('pdf_path');
            $table->json('data'); // Certificate data snapshot
            $table->enum('status', ['generated', 'sent_email', 'sent_whatsapp', 'failed'])->default('generated');
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamp('generated_at');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index('serial_number');
            $table->index(['applicant_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificates');
    }
};
