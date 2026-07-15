<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_items', function (Blueprint $table) {
            if (! Schema::hasColumn('content_items', 'media_type')) {
                $table->string('media_type')->nullable()->after('content');
            }

            if (! Schema::hasColumn('content_items', 'media_title')) {
                $table->string('media_title')->nullable()->after('media_type');
            }

            if (! Schema::hasColumn('content_items', 'media_url')) {
                $table->string('media_url', 2048)->nullable()->after('media_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('content_items', function (Blueprint $table) {
            if (Schema::hasColumn('content_items', 'media_url')) {
                $table->dropColumn('media_url');
            }

            if (Schema::hasColumn('content_items', 'media_title')) {
                $table->dropColumn('media_title');
            }

            if (Schema::hasColumn('content_items', 'media_type')) {
                $table->dropColumn('media_type');
            }
        });
    }
};
