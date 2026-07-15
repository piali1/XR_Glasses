<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('content_items')) {
            return;
        }

        Schema::create('content_items', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->string('version')->default('v1.0');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('area')->nullable();
            $table->string('responsible_role')->nullable();
            $table->string('approval_status')->default('draft');
            $table->string('process')->nullable();
            $table->unsignedInteger('step_number')->nullable();
            $table->string('display_context')->default('workflow');
            $table->text('content');
            $table->timestamps();

            $table->index(['process', 'step_number']);
            $table->index(['type', 'area']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_items');
    }
};
