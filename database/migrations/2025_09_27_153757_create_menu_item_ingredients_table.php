<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // If table already exists, don't recreate it
        if (Schema::hasTable('menu_item_ingredients')) {
            // (Optional) ensure important columns / indexes exist
            Schema::table('menu_item_ingredients', function (Blueprint $table) {
                if (! Schema::hasColumn('menu_item_ingredients', 'qty')) {
                    $table->decimal('qty', 12, 3)->after('stock_item_id');
                }
                // Unique pair safeguard
                $tableExistsUnique = false;
                // If your Laravel version supports it, you can skip this manual check
                // and just call unique() inside a try/catch.
                try {
                    $table->unique(['menu_item_id','stock_item_id']);
                } catch (\Throwable $e) {
                    // index already exists – ignore
                }
            });

            return; // <-- important: stop here
        }

        // Fresh create
        Schema::create('menu_item_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->decimal('qty', 12, 3);
            $table->timestamps();

            $table->unique(['menu_item_id','stock_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_item_ingredients');
    }
};
