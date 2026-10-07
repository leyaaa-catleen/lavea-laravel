<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->decimal('total', 10, 2);
            $t->string('status')->default('pending');
            $t->string('phone');
            $t->string('address');
            $t->string('payment');
            $t->string('notes')->nullable();
            $t->timestamps();
        });
        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('perfume_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name');
            $t->decimal('price', 8, 2);
            $t->unsignedInteger('qty');
        });
    }
    public function down(): void { Schema::dropIfExists('order_items'); Schema::dropIfExists('orders'); }
};
