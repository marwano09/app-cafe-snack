<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('receipts', function (Blueprint $t) {
            $t->engine = 'InnoDB';
            $t->id();

            $t->unsignedBigInteger('order_id');
            $t->enum('type', ['KITCHEN','CUSTOMER']);
            $t->decimal('total', 10, 2);

            $t->timestamps();

            $t->index('order_id');
            $t->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('receipts');
    }
};
