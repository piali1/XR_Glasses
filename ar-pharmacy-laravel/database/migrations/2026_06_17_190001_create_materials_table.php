<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('process');
            $table->unsignedInteger('step_number');
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            $table->index(['process', 'step_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
