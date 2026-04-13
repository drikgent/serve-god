<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('role')->default('editor')->after('password');
            $table->text('bio')->nullable()->after('role');
            $table->string('avatar_url')->nullable()->after('bio');
            $table->boolean('is_active')->default(true)->after('avatar_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'bio', 'avatar_url', 'is_active']);
        });
    }
};
