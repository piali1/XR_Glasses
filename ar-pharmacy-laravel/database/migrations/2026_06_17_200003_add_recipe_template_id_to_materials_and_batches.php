<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'recipe_template_id')) {
                $table->foreignId('recipe_template_id')->nullable()->after('id')->constrained('recipe_templates')->nullOnDelete();
            }
        });

        Schema::table('batches', function (Blueprint $table) {
            if (!Schema::hasColumn('batches', 'recipe_template_id')) {
                $table->foreignId('recipe_template_id')->nullable()->after('id')->constrained('recipe_templates')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'recipe_template_id')) {
                $table->dropConstrainedForeignId('recipe_template_id');
            }
        });

        Schema::table('batches', function (Blueprint $table) {
            if (Schema::hasColumn('batches', 'recipe_template_id')) {
                $table->dropConstrainedForeignId('recipe_template_id');
            }
        });
    }
};
