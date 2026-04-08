<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'advisor'])->default('advisor');
            $table->string('avatar')->nullable();
            $table->boolean('is_online')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
        User::create(['name' => 'Admin','email' => 'admin@themesbrand.com','password' => Hash::make('12345678'),'email_verified_at'=> now(), 'created_at' => now(), 'role' => 'admin']);
        User::create(['name' => 'Ana García','email' => 'ana@chatdesk.com','password' => Hash::make('password'),'email_verified_at'=> now(), 'created_at' => now(), 'role' => 'advisor']);
        User::create(['name' => 'María López','email' => 'maria@chatdesk.com','password' => Hash::make('password'),'email_verified_at'=> now(), 'created_at' => now(), 'role' => 'advisor']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
