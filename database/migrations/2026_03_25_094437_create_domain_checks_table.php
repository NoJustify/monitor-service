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
        Schema::create('domain_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->unsignedSmallInteger('response_time')->nullable();
            $table->boolean('is_success');
            $table->boolean('content_matched')->nullable(); // чи знайдено очікуваний контент
            $table->text('error')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['domain_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_checks');
    }
};
