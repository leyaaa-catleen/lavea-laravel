<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('perfumes', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('category');          // Floral, Woody, Fresh, Oriental
            $t->text('notes')->nullable();   // scent notes
            $t->decimal('price', 8, 2);
            $t->unsignedInteger('stock')->default(0);
            $t->string('color', 7)->default('#9b6bb5'); // bottle colour
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('perfumes'); }
};
