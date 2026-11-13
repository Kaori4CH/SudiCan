<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('waste_price_id')->nullable()->constrained('waste_prices')->nullOnDelete();
            $table->string('waste_type');
            $table->decimal('weight', 8, 2)->default(0);
            $table->decimal('value', 12, 2)->default(0);
            $table->integer('points')->default(0);
            $table->foreignId('validator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('validator_name')->nullable();
            $table->date('deposit_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_deposits');
    }
};
