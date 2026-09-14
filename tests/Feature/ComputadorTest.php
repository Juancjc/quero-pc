<?php

use App\Models\ComponenteComputador;
use App\Models\Computador;
use App\Models\PecaDesejada;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('computador.index'));

    $response->assertRedirect(route('login'));
});

test('user only sees their own computadores', function () {
    $user = User::factory()->create();
    $outroUsuario = User::factory()->create();

    Computador::factory()->for($user)->create(['nome' => 'Meu PC']);
    Computador::factory()->for($outroUsuario)->create(['nome' => 'PC de outra pessoa']);

    $response = $this->actingAs($user)->get(route('computador.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page->component('Computador/Index')
        ->has('dados', 1)
        ->where('dados.0.nome', 'Meu PC'));
});

test('user can create a computador with peças desejadas', function () {
    $user = User::factory()->create();
    $componente = ComponenteComputador::factory()->create();

    $response = $this->actingAs($user)->post(route('computador.store'), [
        'nome' => 'PC Gamer',
        'descricao' => 'Para jogos',
        'status' => 'Ativo',
        'pecas_desejadas' => [
            [
                'componente_computadore_id' => $componente->id,
                'descricao' => 'RTX 4070',
                'api_pc_id' => 'a1b2c3d4-0000-0000-0000-000000000000',
                'quantidade' => 1,
                'link_inicial' => 'https://loja.com/rtx-4070',
                'valor_inicial' => 3500,
            ],
        ],
    ]);

    $computador = Computador::firstWhere('nome', 'PC Gamer');

    $response->assertRedirect(route('computador.edit', $computador));
    $this->assertModelExists($computador);
    expect($computador->user_id)->toBe($user->id);
    expect($computador->pecasDesejadas)->toHaveCount(1);
    expect($computador->pecasDesejadas->first()->descricao)->toBe('RTX 4070');
    expect($computador->pecasDesejadas->first()->api_pc_id)->toBe('a1b2c3d4-0000-0000-0000-000000000000');
});

test('creating a computador requires nome, status and valid peças', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('computador.store'), [
        'nome' => '',
        'status' => 'Status Inválido',
        'pecas_desejadas' => [
            ['componente_computadore_id' => 999999],
        ],
    ]);

    $response->assertSessionHasErrors([
        'nome',
        'status',
        'pecas_desejadas.0.componente_computadore_id',
        'pecas_desejadas.0.descricao',
        'pecas_desejadas.0.quantidade',
        'pecas_desejadas.0.link_inicial',
        'pecas_desejadas.0.valor_inicial',
    ]);
});

test('user can update a computador syncing peças desejadas', function () {
    $user = User::factory()->create();
    $componente = ComponenteComputador::factory()->create();
    $computador = Computador::factory()->for($user)->create();
    $pecaExistente = PecaDesejada::factory()->for($computador)->for($componente, 'componenteComputador')->create();
    $pecaParaRemover = PecaDesejada::factory()->for($computador)->for($componente, 'componenteComputador')->create();

    $response = $this->actingAs($user)->put(route('computador.update', $computador), [
        'nome' => 'PC Atualizado',
        'descricao' => $computador->descricao,
        'status' => 'Comprado',
        'pecas_desejadas' => [
            [
                'id' => $pecaExistente->id,
                'componente_computadore_id' => $componente->id,
                'descricao' => 'Peça atualizada',
                'quantidade' => 2,
                'link_inicial' => 'https://loja.com/peca-atualizada',
                'valor_inicial' => 999.9,
            ],
        ],
    ]);

    $response->assertRedirect(route('computador.edit', $computador));
    $computador->refresh();

    expect($computador->nome)->toBe('PC Atualizado');
    expect($computador->status)->toBe('Comprado');
    expect($computador->pecasDesejadas)->toHaveCount(1);
    expect($computador->pecasDesejadas->first()->descricao)->toBe('Peça atualizada');
    $this->assertSoftDeleted($pecaParaRemover);
});

test('searching peças returns empty results when the PC Parts API is unreachable', function () {
    $user = User::factory()->create();

    Http::fake(function () {
        throw new ConnectionException('cURL error 7: Failed to connect to server');
    });

    $response = $this->actingAs($user)->getJson(route('computador.pecas.buscar', ['busca' => 'ryzen']));

    $response->assertOk();
    $response->assertJson(['data' => []]);
});

test('user cannot edit or update another user computador', function () {
    $user = User::factory()->create();
    $computador = Computador::factory()->create();

    $this->actingAs($user)->get(route('computador.edit', $computador))->assertForbidden();

    $this->actingAs($user)->put(route('computador.update', $computador), [
        'nome' => 'Tentativa de invasão',
        'status' => 'Ativo',
        'pecas_desejadas' => [],
    ])->assertForbidden();
});
