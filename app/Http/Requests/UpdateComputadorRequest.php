<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateComputadorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $computador = $this->route('computador');

        return $computador?->user_id === $this->user()?->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['Ativo', 'Inativo', 'Comprado', 'Esperando Milagre'])],

            'pecas_desejadas' => ['array'],
            'pecas_desejadas.*.id' => [
                'nullable', 'integer',
                Rule::exists('pecas_desejadas', 'id')->where('computador_id', $this->route('computador')?->id),
            ],
            'pecas_desejadas.*.componente_computadore_id' => ['required', 'integer', 'exists:componentes_computadores,id'],
            'pecas_desejadas.*.descricao' => ['required', 'string', 'max:255'],
            'pecas_desejadas.*.api_pc_id' => ['nullable', 'string', 'max:255'],
            'pecas_desejadas.*.quantidade' => ['required', 'integer', 'min:1'],
            'pecas_desejadas.*.link_inicial' => ['required', 'url', 'max:2048'],
            'pecas_desejadas.*.valor_inicial' => ['required', 'numeric', 'min:0'],
        ];
    }
}
