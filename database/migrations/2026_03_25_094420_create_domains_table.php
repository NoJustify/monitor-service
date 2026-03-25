<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->string('name')->nullable();
            $table->unsignedSmallInteger('check_interval')->default(5);
            $table->unsignedSmallInteger('timeout')->default(10);
            $table->enum('http_method', ['GET', 'HEAD'])->default('GET');
            $table->text('expected_content')->nullable(); // очікуваний HTML/текст
            $table->enum('notify_channel', ['email', 'telegram', 'both', 'none'])->default('email');//канал зв'язку
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_checked_at')->nullable();
            $table->boolean('last_status')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
