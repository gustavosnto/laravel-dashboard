<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AsaasBillingController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://sandbox.asaas.com/api/v3/payments'; // URL para criar cobranças
    }

    // Método para listar todas as cobranças
    public function index()
    {
        try {
            // Busca todas as cobranças usando a API do Asaas
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_TOKEN'), // Token do Asaas no .env
            ])->get($this->baseUrl);

            // Verifica se a resposta foi bem-sucedida
            if ($response->successful()) {
                return response()->json($response->json()['data']);
            } else {
                return response()->json(['message' => 'Erro ao carregar cobranças', 'error' => $response->json()], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao carregar cobranças', 'error' => $e->getMessage()], 500);
        }
    }

    // Método para criar uma nova cobrança
    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $data = $request->validate([
            'customer' => 'required|string', // ID do cliente
            'value' => 'required|numeric', // Valor da cobrança
            'dueDate' => 'required|date', // Data de vencimento
            'description' => 'nullable|string', // Descrição da cobrança
            'externalReference' => 'nullable|string', // Referência externa
            'billingType' => 'required|string', // Tipo de cobrança (BOLETO, etc.)
            'discount' => 'nullable|array',
            'discount.value' => 'nullable|numeric',
            'discount.type' => 'nullable|string',
            'discount.dueDateLimitDays' => 'nullable|integer',
            'interest' => 'nullable|array',
            'interest.value' => 'nullable|numeric',
            'fine' => 'nullable|array',
            'fine.value' => 'nullable|numeric',
            'fine.type' => 'nullable|string',
            'postalService' => 'nullable|boolean',
            'callback' => 'nullable|array',
            'callback.successUrl' => 'nullable|string',
            'callback.autoRedirect' => 'nullable|boolean',
        ]);

        try {
            // Envia a requisição para criar a cobrança
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'access_token' => env('ASAAS_API_TOKEN'), // Token do Asaas no .env
            ])->post($this->baseUrl, [
                'customer' => $data['customer'],
                'billingType' => $data['billingType'],
                'value' => $data['value'],
                'dueDate' => $data['dueDate'],
                'description' => $data['description'] ?? '',
                'externalReference' => $data['externalReference'] ?? '',
                'daysAfterDueDateToRegistrationCancellation' => $data['daysAfterDueDateToRegistrationCancellation'] ?? 1,
                'installmentCount' => $data['installmentCount'] ?? null,
                'totalValue' => $data['totalValue'] ?? null,
                'installmentValue' => $data['installmentValue'] ?? null,
                'discount' => $data['discount'] ?? null,
                'interest' => $data['interest'] ?? null,
                'fine' => $data['fine'] ?? null,
                'postalService' => $data['postalService'] ?? false,
                'split' => $data['split'] ?? [],
                'callback' => $data['callback'] ?? null,
            ]);

            // Verifica se a resposta foi bem-sucedida
            if ($response->successful()) {
                return response()->json($response->json(), 201);
            } else {
                return response()->json(['message' => 'Erro ao criar cobrança', 'error' => $response->json()], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar cobrança', 'error' => $e->getMessage()], 500);
        }
    }

    // Método para mostrar os detalhes de uma cobrança
    public function show($id)
    {
        // Busca a cobrança específica da API do Asaas
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->get("{$this->baseUrl}/{$id}");

        // Verifica se a resposta foi bem-sucedida
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['message' => 'Cobrança não encontrada'], 404);
        }
    }

    // Método para atualizar os dados de uma cobrança
    public function update(Request $request, $id)
    {
        // Validação dos dados da cobrança a ser atualizada
        $data = $request->validate([
            'value' => 'required|numeric',
            'dueDate' => 'required|date',
            'description' => 'nullable|string',
        ]);

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_TOKEN'),
            ])->put("{$this->baseUrl}/{$id}", [
                'value' => $data['value'],
                'dueDate' => $data['dueDate'],
                'description' => $data['description'] ?? '',
            ]);

            if ($response->successful()) {
                return response()->json($response->json(), 200);
            } else {
                return response()->json(['message' => 'Erro ao atualizar cobrança', 'error' => $response->json()], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar cobrança', 'error' => $e->getMessage()], 500);
        }
    }

    // Método para excluir uma cobrança
    public function destroy($id)
    {
        // Envia a requisição para excluir uma cobrança
        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_TOKEN'),
            ])->delete("{$this->baseUrl}/{$id}");

            if ($response->successful()) {
                return response()->json(['message' => 'Cobrança deletada com sucesso!'], 200);
            } else {
                return response()->json(['message' => 'Erro ao deletar cobrança', 'error' => $response->json()], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar cobrança', 'error' => $e->getMessage()], 500);
        }
    }
}