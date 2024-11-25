<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AsaasSubscriptionController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://sandbox.asaas.com/api/v3/subscriptions';
    }

    public function index()
    {
        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_TOKEN'),
            ])->get($this->baseUrl);

            if ($response->successful()) {
                return response()->json($response->json()['data']);
            } else {
                return response()->json(['message' => 'Erro ao carregar cobranças', 'error' => $response->json()], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao carregar cobranças', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer' => 'required|string',
            'value' => 'required|numeric',
            'dueDate' => 'required|date',
            'description' => 'nullable|string',
            'externalReference' => 'nullable|string',
            'billingType' => 'required|string',
            'discount' => 'nullable|array',
            'interest' => 'nullable|array',
            'fine' => 'nullable|array',
            'postalService' => 'nullable|boolean',
        ]);

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'access_token' => env('ASAAS_API_TOKEN'),
            ])->post($this->baseUrl, [
                'customer' => $data['customer'],
                'billingType' => $data['billingType'],
                'value' => $data['value'],
                'dueDate' => $data['dueDate'],
                'description' => $data['description'] ?? '',
                'externalReference' => $data['externalReference'] ?? '',
                'discount' => $data['discount'] ?? null,
                'interest' => $data['interest'] ?? null,
                'fine' => $data['fine'] ?? null,
                'postalService' => $data['postalService'] ?? false,
            ]);

            if ($response->successful()) {
                return redirect()->route('financial.subscription.index')->with('success', 'Assinatura criada com sucesso!');
            } else {
                return redirect()->route('financial.subscription.create')->withErrors(['message' => 'Erro ao criar assinatura: ' . $response->json()['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->route('financial.subscription.create')->withErrors(['message' => 'Erro ao criar assinatura: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->get("{$this->baseUrl}/{$id}");

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['message' => 'Cobrança não encontrada'], 404);
        }
    }

    public function update(Request $request, $id)
    {
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

    public function destroy($id)
    {
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
