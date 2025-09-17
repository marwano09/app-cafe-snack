<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $t) {
            $t->engine = 'InnoDB';
            $t->id();
            $t->enum('status', ['PENDING','PREPARING','READY','CANCELLED'])->default('PENDING');
            $t->unsignedInteger('table_number')->nullable();
            $t->text('notes')->nullable();
            $t->decimal('total', 10, 2)->default(0);

            // FK -> users (nullable)
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
