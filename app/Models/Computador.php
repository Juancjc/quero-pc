<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Computador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'computadores';

    //    public $timestamps = true;
    //    public $softDeletes = true;
    protected $fillable = [
        'nome',
        'descricao',
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
