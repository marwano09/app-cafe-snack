<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('stock_movements')) {
            // Table already exists: ensure critical columns / indexes are present
            Schema::table('stock_movements', function (Blueprint $table) {
                if (! Schema::hasColumn('stock_movements', 'stock_item_id')) {
                    $table->foreignId('stock_item_id')->after('id')->constrained()->cascadeOnDelete();
                }

                if (! Schema::hasColumn('stock_movements', 'type')) {
                    // If your DB doesn’t support enum easily, you can use string(20)
                    $table->enum('type', ['PURCHASE','CONSUME','ADJUST'])->after('stock_item_id');
                }

                if (! Schema::hasColumn('stock_movements', 'qty_change')) {
                    $table->decimal('qty_change', 12, 3)->after('type');
                }

                if (! Schema::hasColumn('stock_movements', 'note')) {
                    $table->string('note')->nullable()->after('qty_change');
                }

                if (! Schema::hasColumn('stock_movements', 'cost_total')) {
                    $table->decimal('cost_total', 12, 2)->nullable()->after('note');
                }

                if (! Schema::hasColumn('stock_movements', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('cost_total')
                          ->constrained('users')->nullOnDelete();
                }

                // timestamps if missing
                if (! Schema::hasColumn('stock_movements', 'created_at')) {
                    $table->timestamps();
                }

                // helpful indexes
                try { $table->index(['stock_item_id']); } catch (\Throwable $e) {}
                try { $table->index(['type']); } catch (\Throwable $e) {}
                try { $table->index(['created_at']); } catch (\Throwable $e) {}
            });

            return; // IMPORTANT: stop here if table already exists
        }

        // Fresh create
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['PURCHASE','CONSUME','ADJUST']);
            $table->decimal('qty_change', 12, 3);
            $table->string('note')->nullable();
            $table->decimal('cost_total', 12, 2)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['stock_item_id']);
            $table->index(['type']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
