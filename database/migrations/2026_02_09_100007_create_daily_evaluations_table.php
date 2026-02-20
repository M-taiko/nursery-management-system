<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_evaluations', function (Blueprint $table) {
            $table->id();
            $table->date('evaluation_date');
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->enum('understanding_level', ['excellent', 'very_good', 'good', 'average', 'needs_improvement']);
            $table->unsignedTinyInteger('comprehension_percentage')->default(0);
            $table->text('curriculum_progress')->nullable();
            $table->text('homework')->nullable();
            $table->enum('class_performance', ['excellent', 'very_good', 'good', 'average', 'needs_improvement']);
            $table->enum('behavior', ['excellent', 'very_good', 'good', 'average', 'needs_improvement']);
            $table->text('teacher_notes')->nullable();
            $table->boolean('is_absent')->default(false);
            $table->string('absence_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['evaluation_date', 'child_id', 'subject_id'], 'daily_eval_unique');
            $table->index(['child_id', 'evaluation_date']);
            $table->index('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_evaluations');
    }
};
