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
        Schema::create('routine_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('routine_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->unsignedInteger('progress')->default(0);
            $table->unsignedInteger('target_count');
            $table->date('target_date');
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routine_goals');
    }
};
