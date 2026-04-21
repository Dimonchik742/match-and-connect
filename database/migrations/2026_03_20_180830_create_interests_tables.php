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
        // 1. Таблиця-довідник усіх інтересів
        Schema::create('interests', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Назва інтересу (наприклад, "The Witcher 3")
            $table->timestamps();
        });

        // 2. Зв'язуюча таблиця (Pivot) між користувачами та інтересами
        Schema::create('interest_user', function (Blueprint $table) {
            $table->id();

            // foreignId автоматично створює зв'язок з іншими таблицями
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('interest_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interests_tables');
    }
};
