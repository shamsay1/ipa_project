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
        Schema::table('cr_info', function (Blueprint $table) {
            $table->unsignedBigInteger('semester_id')->nullable()->after('course_id');

            // optional foreign key
            $table->foreign('semester_id')
                  ->references('id')
                  ->on('semesters')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cr_info', function (Blueprint $table) {
            //
        });
    }
};
