<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE applicants MODIFY COLUMN status ENUM('pending', 'in_verification', 'verified', 'rejected', 'certificate_generated') DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE applicants MODIFY COLUMN status ENUM('pending', 'verified', 'rejected', 'certificate_generated') DEFAULT 'pending'");
    }
};
