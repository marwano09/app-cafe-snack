<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // orders
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'orders_status_created_at_idx'); // composite
            $table->index('user_id', 'orders_user_id_idx');
        });

        // order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id', 'order_items_order_id_idx');
            $table->index('menu_item_id', 'order_items_menu_item_id_idx');
        });

        // menu_items
        Schema::table('menu_items', function (Blueprint $table) {
            $table->index('category_id', 'menu_items_category_id_idx');
            $table->index('name', 'menu_items_name_idx');
        });

        // categories
        Schema::table('categories', function (Blueprint $table) {
            $table->index('name', 'categories_name_idx');
        });
    }

    public function down(): void
    {
        // Drop indexes (names must match above)
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_created_at_idx');
            $table->dropIndex('orders_user_id_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_order_id_idx');
            $table->dropIndex('order_items_menu_item_id_idx');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropIndex('menu_items_category_id_idx');
            $table->dropIndex('menu_items_name_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_name_idx');
        });
    }
};
