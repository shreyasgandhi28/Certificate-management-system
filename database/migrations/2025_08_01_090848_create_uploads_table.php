<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['tenth', 'twelfth', 'graduation', 'masters']);
            $table->string('original_filename');
            $table->string('stored_filename');
            $table->string('file_path');
            $table->string('file_hash');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verifier_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('verification_comments')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('uploader_ip');
            $table->timestamps();
            
            $table->index(['applicant_id', 'type']);
            $table->index('verification_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('uploads');
    }
};
