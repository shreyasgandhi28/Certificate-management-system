<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('date_of_birth');
            $table->string('token')->unique();
            $table->enum('status', ['pending', 'verified', 'rejected', 'certificate_generated'])->default('pending');
            $table->json('educational_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();
            
            $table->index(['status', 'submitted_at']);
            $table->index('token');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicants');
    }
};
