<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('post_likes')) {
            return;
        }

        Schema::create('post_likes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['post_id', 'user_id']);
        });

        if (Schema::hasTable('posts')) {
            DB::table('posts')->update(['likes_count' => 0]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('post_likes');
    }
};
