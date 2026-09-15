<?php

namespace App\Http\Controllers;

use App\Models\ComponenteComputador;
use App\Models\Computador;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $componentes = ComponenteComputador::all();
        $computadoresTotal = Computador::all()->count();
        $usuariosTotal = User::all()->count();
        return inertia('Dashboard', [
            'componentes' => $componentes,
            'computadoresTotal' =>$computadoresTotal,
            'usuariosTotal' => $usuariosTotal,
        ]);
    }
}
