<?php

namespace App\Services\PcParts;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Cliente HTTP para a PC Parts Catalog API (projeto api-pc), usada para
 * pesquisar peças de computador (nome, fabricante, modelo, série, categoria)
 * ao cadastrar componentes.
 *
 * Autenticação por token pessoal fixo (Authorization: Bearer pcapi_...),
 * sem fluxo de login: o token já é criado ativo diretamente no banco do
 * api-pc para esta integração.
 */
class PcPartsClient
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function categories(): array
    {
        return (array) ($this->get('/categories') ?? []);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function category(string $slug): ?array
    {
        return $this->get("/categories/{$slug}");
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function subcategories(string $slug): array
    {
        return (array) ($this->get("/categories/{$slug}/subcategories") ?? []);
    }

    /**
     * Lista peças de uma categoria, aceitando os mesmos filtros de parts().
     *
     * @param  array<string, mixed>  $filters
     * @return array{data: array<int, array<string, mixed>>, meta: array<string, mixed>}
     */
    public function categoryParts(string $slug, array $filters = []): array
    {
        return $this->paginated($this->get("/categories/{$slug}/parts", $filters));
    }

    /**
     * Lista peças com filtros combináveis: search, category, subcategory,
     * manufacturer, model, series, page, limit, sort, order.
     *
     * @param  array<string, mixed>  $filters
     * @return array{data: array<int, array<string, mixed>>, meta: array<string, mixed>}
     */
    public function parts(array $filters = []): array
    {
        return $this->paginated($this->get('/parts', $filters));
    }

    /**
     * Busca simples por nome (com apoio em model/series), ordenada por
     * similaridade. Ideal para autocomplete no cadastro de peças.
     *
     * @return array{data: array<int, array<string, mixed>>, meta: array<string, mixed>}
     */
    public function search(string $query, int $page = 1, int $limit = 20): array
    {
        return $this->paginated($this->get('/parts/search', [
            'q' => $query,
            'page' => $page,
            'limit' => $limit,
        ]));
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $id): ?array
    {
        return $this->get("/parts/{$id}");
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>|null
     */
    private function get(string $uri, array $query = []): ?array
    {
        try {
            $response = $this->client()->get($uri, array_filter(
                $query,
                static fn ($value) => $value !== null && $value !== ''
            ));
        } catch (ConnectionException $exception) {
            Log::warning('Falha de conexão com a PC Parts API.', [
                'uri' => $uri,
                'message' => $exception->getMessage(),
            ]);

            throw new PcPartsApiException('Não foi possível conectar à PC Parts API.', 503);
        }

        return $this->toArray($response, $uri);
    }

    private function client(): PendingRequest
    {
        $baseUrl = rtrim((string) config('services.pcapi.base_url'), '/');

        return Http::baseUrl($baseUrl)
            ->timeout((int) config('services.pcapi.timeout', 15))
            ->acceptJson()
            ->withToken((string) config('services.pcapi.token'));
    }

    /**
     * @return array{data: array<int, array<string, mixed>>, meta: array<string, mixed>}
     */
    private function paginated(?array $response): array
    {
        return [
            'data' => (array) ($response['data'] ?? []),
            'meta' => (array) ($response['meta'] ?? []),
        ];
    }

    private function toArray(Response $response, string $uri): ?array
    {
        if (! $response->successful()) {
            $body = $response->json();
            $message = is_array($body) ? (string) ($body['message'] ?? 'Erro na PC Parts API.') : 'Erro na PC Parts API.';
            $code = is_array($body) ? $body['code'] ?? null : null;

            Log::warning('Falha ao consultar a PC Parts API.', [
                'uri' => $uri,
                'status' => $response->status(),
                'code' => $code,
            ]);

            throw new PcPartsApiException($message, $response->status(), $code);
        }

        $json = $response->json();

        return is_array($json) ? $json : null;
    }
}
