<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();          // e.g. "Water 0.5L", "Coca-Cola 33cl", "Red Bull 25cl"
            $table->string('unit')->default('pcs');    // pcs | ml | g (optional, just for info)
            $table->integer('stock_qty')->default(0);  // current stock
            $table->integer('reorder_level')->default(0); // warn threshold
            $table->timestamps();
        });

        Schema::create('ingredient_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['IN','OUT','ADJUST'])->index();
            $table->integer('quantity');          // +IN, -OUT, +/- ADJUST
            $table->string('reason')->nullable(); // "purchase", "sale (order #123)", "waste"
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // pivot: "recipe": how much of each ingredient a menu item consumes per 1 unit sold
        Schema::create('menu_item_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->decimal('qty_per_unit', 10, 3); // e.g. 1 pcs; or 100 ml; or 18 g
            $table->unique(['menu_item_id','ingredient_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('menu_item_ingredients');
        Schema::dropIfExists('ingredient_movements');
        Schema::dropIfExists('ingredients');
    }
};
