<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if (!Schema::hasColumn('menu_items', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name');
            }
            if (!Schema::hasColumn('menu_items', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('menu_items', 'image_path')) {
                $table->string('image_path')->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('menu_items', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if (Schema::hasColumn('menu_items', 'is_available')) {
                $table->dropColumn('is_available');
            }
            if (Schema::hasColumn('menu_items', 'image_path')) {
                $table->dropColumn('image_path');
            }
            if (Schema::hasColumn('menu_items', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('menu_items', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};
