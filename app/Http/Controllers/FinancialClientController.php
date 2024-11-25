<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class FinancialClientController extends Controller
{
    private $baseUrl = 'https://sandbox.asaas.com/api/v3/customers';

    public function index()
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->get($this->baseUrl);

        return Inertia::render('Dashboard/Financial/Client/Index', [
            'customers' => $response->successful() ? $response->json()['data'] : [],
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Financial/Client/Create');
    }

    public function store(Request $request)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->post($this->baseUrl, $request->all());

        if ($response->successful()) {
            return redirect()->route('financial.client.index')->with('success', 'Cliente criado com sucesso!');
        } else {
            return redirect()->route('financial.client.create')->withErrors(['message' => 'Erro ao criar cliente: ' . $response->json()['message']]);
        }
    }

    public function show($id)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->get("{$this->baseUrl}/{$id}");

        if ($response->successful()) {
            return Inertia::render('Dashboard/Financial/Client/Show', [
                'customer' => $response->json(),
            ]);
        } else {
            return redirect()->route('financial.client.index')->withErrors(['message' => 'Cliente não encontrado']);
        }
    }

    public function edit($id)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->get("{$this->baseUrl}/{$id}");

        if ($response->successful()) {
            return Inertia::render('Dashboard/Financial/Client/Edit', [
                'customer' => $response->json(),
            ]);
        } else {
            return redirect()->route('financial.client.index')->withErrors(['message' => 'Cliente não encontrado']);
        }
    }

    public function update(Request $request, $id)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->put("{$this->baseUrl}/{$id}", $request->all());

        if ($response->successful()) {
            return redirect()->route('financial.client.index')->with('success', 'Cliente atualizado com sucesso!');
        } else {
            return redirect()->route('financial.client.edit', $id)->withErrors(['message' => 'Erro ao atualizar cliente: ' . $response->json()['message']]);
        }
    }

    public function destroy($id)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->delete("{$this->baseUrl}/{$id}");

        if ($response->successful()) {
            return redirect()->route('financial.client.index')->with('success', 'Cliente deletado com sucesso!');
        } else {
            return redirect()->route('financial.client.index')->withErrors(['message' => 'Erro ao deletar cliente: ' . $response->json()['message']]);
        }
    }
}
