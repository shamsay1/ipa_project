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
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('teacher_id');
        $table->unsignedBigInteger('subject_id');
        $table->unsignedBigInteger('timetable_id');
        $table->date('date');
        $table->enum('status', ['present', 'absent']);
        $table->timestamps();

        $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('cascade');

        $table->unique(['teacher_id','timetable_id','date']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
    }
};
