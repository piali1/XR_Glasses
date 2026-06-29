<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_template_id')->constrained('recipe_templates')->cascadeOnDelete();
            $table->unsignedInteger('step_number');
            $table->string('title');
            $table->text('description');
            $table->text('warning')->nullable();
            $table->text('ar_hint')->nullable();
            $table->string('risk')->default('medium');
            $table->unsignedInteger('timer_seconds')->default(0);
            $table->json('checklist_items')->nullable();
            $table->timestamps();

            $table->unique(['recipe_template_id', 'step_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_steps');
    }
};
