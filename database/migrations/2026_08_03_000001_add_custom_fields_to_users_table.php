<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin'])->default('user')->after('password');
            $table->string('avatar')->nullable()->after('role');
            $table->boolean('is_banned')->default(false)->after('avatar');
            $table->unsignedInteger('ai_questions_used')->default(0)->after('is_banned');
            $table->timestamp('ai_questions_reset_at')->nullable()->after('ai_questions_used');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'avatar', 'is_banned', 'ai_questions_used', 'ai_questions_reset_at']);
        });
    }
};
