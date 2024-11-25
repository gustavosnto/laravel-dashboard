<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class FinancialController extends Controller
{
    public function index()
    {
        // Este método pode servir para exibir uma visão geral do setor financeiro, como gráficos ou resumos
        return Inertia::render('Dashboard/Financial/Index');
    }
}