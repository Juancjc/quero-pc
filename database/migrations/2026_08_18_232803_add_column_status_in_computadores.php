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
        Schema::table('computadores', function (Blueprint $table) {
            $table->enum('status', ['Ativo', 'Inativo', 'Comprado', 'Esperando Milagre'])->default('ativo')->after('descricao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('computadores', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
