<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->unsignedInteger('upvotes_count')->default(0)->after('views_count');
        });

        Schema::table('forum_replies', function (Blueprint $table) {
            $table->unsignedInteger('upvotes_count')->default(0)->after('is_spam');
        });

        Schema::create('forum_upvotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('upvotable_type');
            $table->unsignedBigInteger('upvotable_id');
            $table->timestamps();

            $table->unique(['user_id', 'upvotable_type', 'upvotable_id']);
            $table->index(['upvotable_type', 'upvotable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_upvotes');

        Schema::table('forum_replies', function (Blueprint $table) {
            $table->dropColumn('upvotes_count');
        });

        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn('upvotes_count');
        });
    }
};
