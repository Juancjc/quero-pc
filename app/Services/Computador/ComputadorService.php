<?php

namespace App\Services\Computador;

use App\Models\Computador;
use Illuminate\Support\Facades\DB;

/**
 * Cria e atualiza um computador junto com a lista de peças desejadas
 * que vieram do formulário (Computador/Form.vue).
 */
class ComputadorService
{
    /**
     * @param  array<string, mixed>  $dados
     */
    public function criar(array $dados, int $userId): Computador
    {
        return DB::transaction(function () use ($dados, $userId) {
            $computador = Computador::create([
                'nome' => $dados['nome'],
                'descricao' => $dados['descricao'] ?? '',
                'status' => $dados['status'],
                'user_id' => $userId,
            ]);

            $this->sincronizarPecasDesejadas($computador, $dados['pecas_desejadas'] ?? []);

            return $computador->load('pecasDesejadas.componenteComputador');
        });
    }

    /**
     * @param  array<string, mixed>  $dados
     */
    public function atualizar(Computador $computador, array $dados): Computador
    {
        return DB::transaction(function () use ($computador, $dados) {
            $computador->update([
                'nome' => $dados['nome'],
                'descricao' => $dados['descricao'] ?? '',
                'status' => $dados['status'],
            ]);

            $this->sincronizarPecasDesejadas($computador, $dados['pecas_desejadas'] ?? []);

            return $computador->load('pecasDesejadas.componenteComputador');
        });
    }

    /**
     * Cria as peças novas, atualiza as existentes e remove as que o aluno
     * tirou do formulário.
     *
     * @param  array<int, array<string, mixed>>  $pecas
     */
    private function sincronizarPecasDesejadas(Computador $computador, array $pecas): void
    {
        $idsMantidos = [];

        foreach ($pecas as $peca) {
            $dadosPeca = [
                'componente_computadore_id' => $peca['componente_computadore_id'],
                'descricao' => $peca['descricao'],
                'api_pc_id' => $peca['api_pc_id'] ?? null,
                'quantidade' => $peca['quantidade'],
                'link_inicial' => $peca['link_inicial'],
                'valor_inicial' => $peca['valor_inicial'],
            ];

            $pecaDesejada = ! empty($peca['id'])
                ? $computador->pecasDesejadas()->whereKey($peca['id'])->first()
                : null;

            if ($pecaDesejada) {
                $pecaDesejada->update($dadosPeca);
            } else {
                $pecaDesejada = $computador->pecasDesejadas()->create($dadosPeca);
            }

            $idsMantidos[] = $pecaDesejada->id;
        }

        $computador->pecasDesejadas()->whereNotIn('id', $idsMantidos)->delete();
    }
}
