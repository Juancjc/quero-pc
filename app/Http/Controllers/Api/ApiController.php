<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Computador;
use App\Models\User;

class ApiController extends Controller
{
    public function totalSistema()
    {
        $computadoresTotal = Computador::all()->count();
        $usuariosTotal = User::all()->count();
        return json_encode([
            'computadoresTotal' => $computadoresTotal,
            'usuariosTotal' => $usuariosTotal
        ]);
    }
}
