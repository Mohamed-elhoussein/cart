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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer("number");
            $table->enum("type",[1,2,3]);
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // مفتاح خارجي للمستخدم
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade'); //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
