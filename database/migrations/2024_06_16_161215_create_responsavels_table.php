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
        Schema::create('responsavel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_aluno')->nullable()->constrained('alunos')->nullOnDelete();
            $table->string('nome', 100);
            $table->string('email');
            $table->integer('telefone');
            $table->string('senha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsavels');
    }
};
