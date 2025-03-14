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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('firstname');
            $table->string('lastname');
            $table->date('birthdate');
            $table->longText('pfp');
            $table->string('username');
            $table->string('email')->unique();
            $table->foreign('email')->references('email')->on("emails")->onDelete("cascade");
            $table->string('password');
            $table->rememberToken();
            $table->boolean("quit")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
