<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('book')->cascadeOnDelete();
            $table->foreignId('borrower_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('loan_id')->nullable()->unique()->constrained('loan')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_request');
    }
};
