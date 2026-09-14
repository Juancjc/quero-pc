<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ao cadastrar uma peça desejada o aluno só sabe o link e o valor que
     * encontrou (link_inicial/valor_inicial). Caminho (anexo), link_final e
     * valor_ultimo_encontrado só existem depois, quando a peça é comprada ou
     * um novo preço é encontrado, então não podem ser obrigatórios.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE pecas_desejadas ALTER COLUMN caminho DROP NOT NULL');
        DB::statement('ALTER TABLE pecas_desejadas ALTER COLUMN link_final DROP NOT NULL');
        DB::statement('ALTER TABLE pecas_desejadas ALTER COLUMN valor_ultimo_encontrado DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE pecas_desejadas ALTER COLUMN caminho SET NOT NULL');
        DB::statement('ALTER TABLE pecas_desejadas ALTER COLUMN link_final SET NOT NULL');
        DB::statement('ALTER TABLE pecas_desejadas ALTER COLUMN valor_ultimo_encontrado SET NOT NULL');
    }
};
