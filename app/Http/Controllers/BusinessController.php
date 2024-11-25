<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::all(); 
        return Inertia::render('Dashboard/Business/Index', [
            'businesses' => $businesses,
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Business/Create');
    }

    public function store(Request $request)
    {
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
        
        return redirect()->route('business.index');
    }

    public function show($id)
    {
        $business = Business::findOrFail($id);
        
        return Inertia::render('Dashboard/Business/Show', [
            'business' => $business
        ]);
    }

    public function edit($id)
    {
        $business = Business::findOrFail($id);
        
        return Inertia::render('Dashboard/Business/Edit', [
            'business' => $business
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'cnpj' => preg_replace('/\D/', '', $request->cnpj),
            'cep' => preg_replace('/\D/', '', $request->cep),
            'telefone' => preg_replace('/\D/', '', $request->telefone),
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
        
        return redirect()->route('business.index');
    }

    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();
        
        return redirect()->route('business.index');
    }
}
