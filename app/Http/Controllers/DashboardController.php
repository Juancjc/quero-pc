<?php

namespace App\Http\Controllers;

use App\Models\ComponenteComputador;

class DashboardController extends Controller
{
    public function index()
    {
        $componentes = ComponenteComputador::all();

        return inertia('Dashboard', [
            'componentes' => $componentes,
        ]);
    }
}
