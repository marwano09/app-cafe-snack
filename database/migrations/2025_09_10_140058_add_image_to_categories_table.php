<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'name')) {
                $table->string('name')->unique();
            }
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->unique()->nullable();
            }
            if (!Schema::hasColumn('categories', 'image_path')) {
                $table->string('image_path')->nullable();
            }
        });
    }

    public function down(): void {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'image_path')) $table->dropColumn('image_path');
            if (Schema::hasColumn('categories', 'slug'))       $table->dropColumn('slug');
            // keep name (likely required elsewhere)
        });
    }
};
