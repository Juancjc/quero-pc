<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PecaDesejada extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'peca_desejada';

    protected $fillable = [
        'componente_computadore_id',
        'computador_id',
        'caminho',
        'descricao',
        'quantidade',
        'link_inicial',
        'valor_inicial',
        'link_final',
        'valor_ultimo_encontrado',
    ];

    public function componenteComputador()
    {
        return $this->belongsTo(ComponenteComputador::class);
    }

    public function computador()
    {
        return $this->belongsTo(Computador::class);
    }
}
