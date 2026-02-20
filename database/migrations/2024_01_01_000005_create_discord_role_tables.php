<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discord_role_mappings', function (Blueprint $table): void {
            $table->id();
            $table->string('discord_role_id')->unique();
            $table->string('role_name')->nullable();
            $table->string('permission_key');
            $table->timestamps();
        });

        Schema::create('discord_user_roles', function (Blueprint $table): void {
            $table->id();
            $table->string('discord_user_id');
            $table->string('role_id');
            $table->timestamps();

            $table->unique(['discord_user_id', 'role_id']);
            $table->index(['role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discord_user_roles');
        Schema::dropIfExists('discord_role_mappings');
    }
};
