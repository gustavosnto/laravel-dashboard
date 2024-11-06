<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class FinancialController extends Controller
{
    private $baseUrl; // URL base da API do Asaas

    public function __construct()
    {
        $this->baseUrl = 'https://sandbox.asaas.com/api/v3/customers'; // Inicializa a propriedade baseUrl
    }

    public function index()
    {
        // Busca todos os clientes da API do Asaas
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'), // Pega o token do arquivo .env
        ])->get($this->baseUrl);

        // Passa a lista de clientes para a view de index
        return Inertia::render('Dashboard/Financial/Index', [
            'customers' => $response->successful() ? $response->json()['data'] : [], // Se a resposta for bem-sucedida, obtém os dados
        ]);
    }

    public function create()
    {
        // Renderiza a view de criação de clientes
        return Inertia::render('Dashboard/Financial/Create');
    }

    public function store(Request $request)
    {
        // Envia os dados para criar um novo cliente
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->post($this->baseUrl, $request->all());

        // Redireciona de acordo com a resposta da API
        if ($response->successful()) {
            return redirect()->route('financial.index')->with('success', 'Cliente criado com sucesso!');
        } else {
            return redirect()->route('financial.create')->withErrors(['message' => 'Erro ao criar cliente: ' . $response->json()['message']]);
        }
    }

    public function show($id)
    {
        // Busca o cliente específico da API do Asaas
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->get("{$this->baseUrl}/{$id}");

        // Verifica se a resposta foi bem-sucedida
        if ($response->successful()) {
            return Inertia::render('Dashboard/Financial/Show', [
                'customer' => $response->json(), // Passa o cliente para a view
            ]);
        } else {
            return redirect()->route('financial.index')->withErrors(['message' => 'Cliente não encontrado']);
        }
    }

    public function update(Request $request, $id)
    {
        // Envia os dados para atualizar um cliente existente
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->put("{$this->baseUrl}/{$id}", $request->all());

        // Redireciona de acordo com a resposta da API
        if ($response->successful()) {
            return redirect()->route('financial.index')->with('success', 'Cliente atualizado com sucesso!');
        } else {
            return redirect()->route('financial.edit', $id)->withErrors(['message' => 'Erro ao atualizar cliente: ' . $response->json()['message']]);
        }
    }

    public function edit($id)
    {
        // Busca o cliente específico da API do Asaas
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->get("{$this->baseUrl}/{$id}");

        // Verifica se a resposta foi bem-sucedida
        if ($response->successful()) {
            return Inertia::render('Dashboard/Financial/Edit', [
                'customer' => $response->json(), // Passa o cliente para a view
            ]);
        } else {
            return redirect()->route('financial.index')->withErrors(['message' => 'Cliente não encontrado']);
        }
    }

    public function destroy($id)
    {
        // Envia a requisição para deletar um cliente
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->delete("{$this->baseUrl}/{$id}");

        // Redireciona de acordo com a resposta da API
        if ($response->successful()) {
            return redirect()->route('financial.index')->with('success', 'Cliente deletado com sucesso!');
        } else {
            return redirect()->route('financial.index')->withErrors(['message' => 'Erro ao deletar cliente: ' . $response->json()['message']]);
        }
    }
}
