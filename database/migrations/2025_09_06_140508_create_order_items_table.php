<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $t) {
            $t->engine = 'InnoDB';
            $t->id();

            // خليه واضح ومطابق لنوع PK (BIGINT UNSIGNED)
            $t->unsignedBigInteger('order_id');
            $t->unsignedBigInteger('menu_item_id');

            $t->unsignedInteger('quantity');
            $t->decimal('price', 10, 2);

            $t->timestamps();

            // زِد index صريح (بعض الإصدارات تتطلبه قبل FK)
            $t->index('order_id');
            $t->index('menu_item_id');

            $t->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $t->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};
