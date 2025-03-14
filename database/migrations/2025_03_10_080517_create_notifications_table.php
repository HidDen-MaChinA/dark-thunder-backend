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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid("user_id");
            $table->uuid("discussion_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("discussion_id")->references("id")->on("discussions")->onDelete("cascade");
            $table->text("notification_message");
            $table->dateTimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
