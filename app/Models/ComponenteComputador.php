<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComponenteComputador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'componentes_computadores';

    protected $fillable = [
        'nome',
        'descricao',
        'user_cadastro_id',
        'user_atualizacao_id',
        'user_exclusao_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_cadastro_id = auth()->id();
            $model->user_atualizacao_id = auth()->id();
        });

        static::updating(function ($model) {
            $model->user_atualizacao_id = auth()->id();
        });

        static::deleting(function ($model) {
            $model->user_exclusao_id = auth()->id();
            $model->save();
        });
    }

    public function userCadastro()
    {
        return $this->belongsTo(User::class, 'user_cadastro_id', 'id');
    }

    public function userAtualizacao()
    {
        return $this->belongsTo(User::class, 'user_atualizacao_id', 'id');
    }

    public function userExclusao()
    {
        return $this->belongsTo(User::class, 'user_exclusao_id', 'id');
    }

    public function pecasDesejadas()
    {
        return $this->hasMany(PecaDesejada::class);
    }
}
