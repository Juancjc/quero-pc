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
        Schema::create('componentes_computadores', function (Blueprint $table) {
            $table->id();
            $table->text('nome');
            $table->longText('descricao');
            $table->bigInteger('user_cadastro_id')->unsigned('user_cadastro_id')->nullable();
            $table->foreign('user_cadastro_id')->references('id')->on('users');
            $table->bigInteger('user_atualizacao_id')->unsigned('user_atualizacao_id')->nullable();
            $table->foreign('user_atualizacao_id')->references('id')->on('users');
            $table->bigInteger('user_exclusao_id')->unsigned('user_exclusao_id')->nullable();
            $table->foreign('user_exclusao_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('componentes_computadores');
    }
};
