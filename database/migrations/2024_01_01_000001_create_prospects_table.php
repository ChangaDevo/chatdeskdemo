<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('case_description')->nullable();
            $table->enum('status', ['new', 'in_progress', 'qualified', 'disqualified', 'converted'])
                  ->default('new');
            $table->string('case_type')->nullable(); // accidente, laboral, familiar, etc.
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('ai_score')->nullable(); // 0-100
            $table->text('ai_summary')->nullable();
            $table->boolean('bot_active')->default(true);
            $table->string('widget_token')->unique()->nullable(); // para identificar sesión del widget
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
