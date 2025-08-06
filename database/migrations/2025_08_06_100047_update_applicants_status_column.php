<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            // Drop the old status column
            $table->dropColumn('status');
        });
        
        Schema::table('applicants', function (Blueprint $table) {
            // Recreate with new allowed values
            $table->string('status')->default('pending');
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
