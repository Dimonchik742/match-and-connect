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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Хто написав (зв'язок з таблицею users)
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            
            // Кому написали (зв'язок з таблицею users)
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            
            // Сам текст повідомлення
            $table->text('content');
            
            // Статус: прочитано чи ні (за замовчуванням - ні)
            $table->boolean('is_read')->default(false);
            
            $table->timestamps(); // Автоматично додасть час відправки (created_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
