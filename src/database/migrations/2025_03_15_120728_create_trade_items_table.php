<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trade_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_id')
                ->constrained('trades')
                ->onDelete('cascade');
            $table->foreignId('item_id')
                ->constrained('items')
                ->onDelete('cascade');
            $table->foreignId('explorer_id')
                ->constrained('explorers')
                ->onDelete('cascade');
            $table->unsignedInteger('quantity');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_items');
    }
};
