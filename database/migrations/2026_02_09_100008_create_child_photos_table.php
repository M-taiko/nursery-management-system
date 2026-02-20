<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->string('photo_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('activity')->nullable();
            $table->text('description')->nullable();
            $table->date('photo_date');
            $table->unsignedInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['child_id', 'photo_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_photos');
    }
};
