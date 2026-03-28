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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string("subjectName");
            $table->string("subjectCode");
            $table->string("subject_type");
            $table->string("required_lab");
            $table->foreignId("teacher_id")->constrained()->onDelete("cascade");
            $table->foreignId("course_id")->constrained()->onDelete("cascade");
            $table->string("nta_level");
            $table->foreignId("semester_id")->constrained()->onDelete("cascade");
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
