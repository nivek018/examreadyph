<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('forum_threads', 'upvotes_count')) {
            Schema::table('forum_threads', function (Blueprint $table) {
                $table->unsignedInteger('upvotes_count')->default(0)->after('views_count');
            });
        }

        if (!Schema::hasColumn('forum_replies', 'upvotes_count')) {
            Schema::table('forum_replies', function (Blueprint $table) {
                $table->unsignedInteger('upvotes_count')->default(0)->after('is_spam');
            });
        }

        if (!Schema::hasTable('forum_upvotes')) {
            Schema::create('forum_upvotes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                $table->string('ip_address', 45)->nullable();
                $table->string('upvotable_type');
                $table->unsignedBigInteger('upvotable_id');
                $table->timestamps();

                $table->index(['upvotable_type', 'upvotable_id']);
                $table->index(['user_id', 'upvotable_type', 'upvotable_id']);
                $table->index(['ip_address', 'upvotable_type', 'upvotable_id']);
            });
        }

        if (!Schema::hasColumn('forum_reports', 'ip_address')) {
            Schema::table('forum_reports', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->change();
                $table->string('ip_address', 45)->nullable()->after('user_id');
            });
        }
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
