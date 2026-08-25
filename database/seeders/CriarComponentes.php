<?php

namespace Database\Seeders;

use App\Models\ComponenteComputador;
use Illuminate\Database\Seeder;

class CriarComponentes extends Seeder
{
    public function run(): void
    {
        $componentes = [
            [
                'nome' => 'CPU',
                'descricao' => 'Unidade Central de Processamento, responsável por executar instruções e processar dados.',
            ],
            [
                'nome' => 'Placa-mãe',
                'descricao' => 'Placa de circuito principal que interliga e permite a comunicação entre todos os componentes do computador.',
            ],
            [
                'nome' => 'Memória RAM',
                'descricao' => 'Memória de acesso aleatório, utilizada para armazenar dados temporários e instruções em execução.',
            ],
            [
                'nome' => 'Placa de Vídeo',
                'descricao' => 'Responsável pelo processamento e renderização de imagens, vídeos e gráficos exibidos no monitor.',
            ],
            [
                'nome' => 'Armazenamento SSD',
                'descricao' => 'Unidade de estado sólido utilizada para armazenamento permanente de dados, com alta velocidade de leitura e escrita.',
            ],
            [
                'nome' => 'Armazenamento HD',
                'descricao' => 'Disco rígido utilizado para armazenamento permanente de dados em maior capacidade e menor custo por gigabyte.',
            ],
            [
                'nome' => 'Fonte de Alimentação',
                'descricao' => 'Converte a energia da rede elétrica em energia adequada para alimentar todos os componentes do computador.',
            ],
            [
                'nome' => 'Gabinete',
                'descricao' => 'Estrutura física que abriga e protege os componentes internos do computador.',
            ],
            [
                'nome' => 'Cooler',
                'descricao' => 'Sistema de refrigeração responsável por dissipar o calor gerado pelos componentes, mantendo a temperatura ideal.',
            ],
            [
                'nome' => 'Monitor',
                'descricao' => 'Dispositivo de saída utilizado para exibição de imagens e vídeos gerados pelo computador.',
            ],
            [
                'nome' => 'Teclado',
                'descricao' => 'Dispositivo de entrada utilizado para digitação e inserção de comandos no computador.',
            ],
            [
                'nome' => 'Mouse',
                'descricao' => 'Dispositivo de entrada utilizado para navegação e interação com a interface gráfica do computador.',
            ],
        ];
        $componentesDB = ComponenteComputador::all();
        foreach ($componentes as $componente) {
            if (! $componentesDB->contains('nome', $componente['nome'])) {
                ComponenteComputador::create($componente);
            }
        }
    }
}
