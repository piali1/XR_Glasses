<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pharmacist_releases')) {
            return;
        }

        Schema::create('pharmacist_releases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->string('reviewer_name')->nullable();
            $table->string('reviewer_role')->default('pharmacist');
            $table->string('decision');
            $table->string('risk_level')->default('low');
            $table->json('material_summary')->nullable();
            $table->json('checklist_summary')->nullable();
            $table->json('issue_summary')->nullable();
            $table->json('content_version_summary')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacist_releases');
    }
};
