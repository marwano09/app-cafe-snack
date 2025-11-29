<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menu_item_recipes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $t->foreignId('stock_id')->constrained('stocks')->cascadeOnDelete();
            $t->decimal('qty_per_unit', 12, 3); // quantity per 1 menu item
            $t->timestamps();
            $t->unique(['menu_item_id','stock_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('menu_item_recipes'); }
};
