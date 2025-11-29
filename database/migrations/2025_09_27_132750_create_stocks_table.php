<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stocks', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('sku')->nullable()->unique();
            $t->string('unit')->default('piece'); // piece, can, bottle, ml, g …
            $t->decimal('qty_on_hand', 12, 3)->default(0);
            $t->decimal('min_qty', 12, 3)->default(0);
            $t->string('location')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('stocks'); }
};
