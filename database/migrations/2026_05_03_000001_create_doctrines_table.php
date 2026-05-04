<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctrines', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('topic')->index();
            $table->string('source_language')->default('Pali');
            $table->string('language')->index();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('translator')->default('Dhamma Sphere Library');
            $table->boolean('ai_available')->default(true);
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctrines');
    }
};
