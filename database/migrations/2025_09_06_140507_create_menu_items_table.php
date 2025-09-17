<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menu_items', function (Blueprint $t) {
            $t->engine = 'InnoDB';
            $t->id();
            $t->string('name');
            $t->text('description')->nullable();
            $t->decimal('price', 10, 2);
            $t->boolean('is_available')->default(true);

            // FK -> categories
            $t->foreignId('category_id')->constrained('categories')->cascadeOnDelete();

            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('menu_items');
    }
};
