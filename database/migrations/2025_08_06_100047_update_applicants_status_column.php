<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, drop the index that includes the status column if it exists
        Schema::table('applicants', function (Blueprint $table) {
            if (Schema::hasColumn('applicants', 'status')) {
                // For SQLite, we need to recreate the table without the column
                // as it doesn't support dropping columns with indexes directly
                $table->string('status')->default('pending')->change();
            }
        });
    }

    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });
    }
};
