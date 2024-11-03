<?php

namespace App\Http\Controllers;

use App\Models\Business; // Certifique-se de importar o modelo
use Illuminate\Http\Request;
use Inertia\Inertia;

class BusinessController extends Controller
{
    // Método para exibir a lista de negócios
    public function index()
    {
        $businesses = Business::all(); 
        return Inertia::render('Dashboard/Business/Index', [
            'businesses' => $businesses,
        ]);
    }

    // Método para criar um novo negócio
    public function create()
    {
        return Inertia::render('Dashboard/Business/Create'); // Retorne a view para criar um novo negócio
    }

    // Método para armazenar um novo negócio
    public function store(Request $request)
    {
        // Valide e salve o novo negócio
        $request->validate([
            'cnpj' => 'required|string|max:14',
            'razao' => 'required|string|max:255',
            'cep' => 'required|string|max:10',
            'endereco' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'uf' => 'required|string|max:2',
            'cidade' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:businesses',
            'telefone' => 'required|string|max:15',
            'nome_socio' => 'required|string|max:255',
        ]);

        Business::create($request->all());
        
        return redirect()->route('business.index'); // Redirecione para a lista de negócios
    }

    // Método para mostrar um negócio específico
    public function show($id)
    {
        $business = Business::findOrFail($id);
        
        return Inertia::render('Dashboard/Business/Show', [
            'business' => $business
        ]); // Retorne a view para mostrar um negócio
    }

    // Método para editar um negócio
    public function edit($id)
    {
        $business = Business::findOrFail($id);
        
        return Inertia::render('Dashboard/Business/Edit', [
            'business' => $business
        ]); // Retorne a view para editar um negócio
    }

    // Método para atualizar um negócio
    public function update(Request $request, $id)
    {
        // Limpar campos antes da validação
        $request->merge([
            'cnpj' => preg_replace('/\D/', '', $request->cnpj), // Remove todos os caracteres não numéricos
            'cep' => preg_replace('/\D/', '', $request->cep), // Remove todos os caracteres não numéricos
            'telefone' => preg_replace('/\D/', '', $request->telefone), // Remove todos os caracteres não numéricos
        ]);

        $request->validate([
            'cnpj' => 'required|string|max:14',
            'razao' => 'required|string|max:255',
            'cep' => 'required|string|max:10',
            'endereco' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'uf' => 'required|string|max:2',
            'cidade' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:businesses,email,' . $id,
            'telefone' => 'required|string|max:15',
            'nome_socio' => 'required|string|max:255',
        ]);

        $business = Business::findOrFail($id);
        $business->update($request->all());
        
        return redirect()->route('business.index'); // Redirecione para a lista de negócios
    }

    // Método para excluir um negócio
    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();
        
        return redirect()->route('business.index'); // Redirecione para a lista de negócios
    }
}
