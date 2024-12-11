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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // العلاقة مع جدول users
            $table->foreignId('gate_id')->constrained()->onDelete('cascade'); // العلاقة مع جدول gates
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade'); // العلاقة مع جدول wallets
            $table->decimal('amount', 8, 2); // المبلغ الذي تم دفعه
            $table->string('transaction_type'); // نوع المعاملة (دفع، استرداد، إلخ)
            $table->timestamp('transaction_date')->useCurrent(); // تاريخ المعاملة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
