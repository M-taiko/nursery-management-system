<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('behavior_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->date('record_date');
            $table->enum('type', ['positive', 'negative', 'neutral']);
            $table->string('category');
            $table->text('description');
            $table->text('action_taken')->nullable();
            $table->boolean('parent_notified')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['child_id', 'record_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('behavior_records');
    }
};
