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
        Schema::create('pecas_desejadas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('componente_computadore_id')->unsigned();
            $table->foreign('componente_computadore_id')->references('id')->on('componente_computadores');
            $table->bigInteger('computador_id')->unsigned();
            $table->foreign('computador_id')->references('id')->on('computadores');
            // o caminho vai ser o caminho do arquivo da peça desejada, que pode ser uma imagem ou um arquivo de texto
            $table->longText('caminho');
            $table->longText('descricao');
            $table->integer('quantidade');
            $table->longText('link_inicial');
            $table->decimal('valor_inicial', 15, 2);
            $table->longText('link_final');
            $table->decimal('valor_ultimo_encontrado', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pecas_desejadas');
    }
};
