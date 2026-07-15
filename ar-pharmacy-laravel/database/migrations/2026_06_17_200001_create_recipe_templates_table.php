<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_templates', function (Blueprint $table) {
            $table->id();
            $table->string('process');
            $table->string('title');
            $table->string('reference_source')->nullable();
            $table->string('reference_code')->nullable();
            $table->string('dosage_form')->nullable();
            $table->text('source_note')->nullable();
            $table->boolean('is_demo')->default(true);
            $table->timestamps();

            $table->index('process');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_templates');
    }
};
