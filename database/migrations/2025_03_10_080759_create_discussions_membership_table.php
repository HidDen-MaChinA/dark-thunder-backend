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
        Schema::create('discussions_membership', function (Blueprint $table) {
            $table->uuid()->id();
            $table->foreignUuid("discussion_id")->constrained("discussions", "id");
            $table->foreignUuid("user_id")->constrained("users", "id");
            $table->unique(["discussion_id", "user_id"]);
            $table->dateTime("add_date");
            $table->enum("permission", ["read", "write", "mod"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discussions_membership');
    }
};
