<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PecaDesejada extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pecas_desejadas';

    protected $fillable = [
        'componente_computadore_id',
        'computador_id',
        'caminho',
        'descricao',
        'api_pc_id',
        'quantidade',
        'link_inicial',
        'valor_inicial',
        'link_final',
        'valor_ultimo_encontrado',
    ];

    protected function casts(): array
    {
        return [
            'quantidade' => 'integer',
            'valor_inicial' => 'decimal:2',
            'valor_ultimo_encontrado' => 'decimal:2',
        ];
    }

    public function componenteComputador()
    {
        return $this->belongsTo(ComponenteComputador::class, 'componente_computadore_id');
    }

    public function computador()
    {
        return $this->belongsTo(Computador::class);
    }
}
