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
        Schema::create('active_sessions', function (Blueprint $table) {
            $table->id(); // Cria a coluna 'id' como chave primária
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cria a chave estrangeira referenciando a tabela 'users'
            $table->string('ip_address', 45); // Coluna para armazenar o endereço IP (IPv4 ou IPv6)
            $table->string('session_id', 255); // Coluna para armazenar o ID da sessão
            $table->timestamp('last_activity')->useCurrent(); // Coluna para armazenar o último timestamp de atividade
            $table->timestamps(); // Cria as colunas 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_sessions');    }
};
