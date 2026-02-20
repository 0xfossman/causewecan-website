<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guild_members', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('class_name')->nullable();
            $table->string('spec_name')->nullable();
            $table->unsignedInteger('level')->default(1);
            $table->string('race')->nullable();
            $table->string('rank_name')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('armory_url')->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guild_members');
    }
};
