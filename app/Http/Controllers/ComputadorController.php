<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComputadorRequest;
use App\Http\Requests\UpdateComputadorRequest;
use App\Models\ComponenteComputador;
use App\Models\Computador;
use App\Services\Computador\ComputadorService;
use App\Services\PcParts\PcPartsApiException;
use App\Services\PcParts\PcPartsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ComputadorController extends Controller
{
    public function __construct(private readonly ComputadorService $computadorService) {}

    public function index()
    {
        $dados = Computador::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return inertia('Computador/Index', ['dados' => $dados]);
    }

    public function create()
    {
        return inertia('Computador/Form', [
            'dados' => null,
            'componentes' => ComponenteComputador::orderBy('nome')->get(),
        ]);
    }

    public function store(StoreComputadorRequest $request)
    {
        $computador = $this->computadorService->criar($request->validated(), Auth::id());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Computador cadastrado com sucesso.']);

        return to_route('computador.edit', $computador);
    }

    public function show(Computador $computador)
    {
        abort_if($computador->user_id !== Auth::id(), 403);

        return to_route('computador.edit', $computador);
    }

    public function edit(Computador $computador)
    {
        abort_if($computador->user_id !== Auth::id(), 403);

        return inertia('Computador/Form', [
            'dados' => $computador->load('pecasDesejadas.componenteComputador'),
            'componentes' => ComponenteComputador::orderBy('nome')->get(),
        ]);
    }

    public function update(UpdateComputadorRequest $request, Computador $computador)
    {
        $this->computadorService->atualizar($computador, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Computador atualizado com sucesso.']);

        return to_route('computador.edit', $computador);
    }

    public function destroy(Computador $computador)
    {
        abort_if($computador->user_id !== Auth::id(), 403);

        $computador->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Computador removido com sucesso.']);

        return to_route('computador.index');
    }

    /**
     * Busca peças na PC Parts Catalog API para o select de pesquisa do formulário.
     */
    public function buscarPecas(Request $request, PcPartsClient $pcParts)
    {
        $busca = (string) $request->query('busca', '');

        if (mb_strlen($busca) < 2) {
            return response()->json(['data' => []]);
        }

        try {
            return response()->json($pcParts->search($busca));
        } catch (PcPartsApiException) {
            return response()->json(['data' => []]);
        }
    }
}
