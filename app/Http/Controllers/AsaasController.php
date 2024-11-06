<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AsaasController extends Controller
{
    private $baseUrl = 'https://sandbox.asaas.com/api/v3/customers';

    // Método para listar clientes
    public function index()
    {
        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_TOKEN'),
            ])->get($this->baseUrl);

            if ($response->successful()) {
                return response()->json($response->json(), 200);
            } else {
                return response()->json(['error' => 'Erro ao buscar clientes'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->get("https://sandbox.asaas.com/api/v3/customers/{$id}");

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Customer not found'], 404);
        }
    }

    // Método para criar um novo cliente
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cpfCnpj' => 'required|string|max:20', // Adicione validação para CPF/CNPJ
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'mobilePhone' => 'nullable|string|max:15', // Telefone móvel é opcional
            'address' => 'required|string|max:255',
            'addressNumber' => 'required|string|max:10',
            'complement' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postalCode' => 'required|string|max:10',
            'externalReference' => 'nullable|string|max:255',
            'notificationDisabled' => 'nullable|boolean',
            'additionalEmails' => 'nullable|string|max:255',
            'municipalInscription' => 'nullable|string|max:255',
            'stateInscription' => 'nullable|string|max:255',
            'observations' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'groupName' => 'nullable|string|max:255',
            'foreignCustomer' => 'nullable|boolean',
        ]);

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_TOKEN'),
            ])->post($this->baseUrl, $data);

            if ($response->successful()) {
                return response()->json($response->json(), 201);
            } else {
                return response()->json(['error' => 'Erro ao criar cliente'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método para atualizar um cliente existente
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cpfCnpj' => 'required|string|max:20', // Adicione validação para CPF/CNPJ
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'mobilePhone' => 'nullable|string|max:15', // Telefone móvel é opcional
            'address' => 'required|string|max:255',
            'addressNumber' => 'required|string|max:10',
            'complement' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postalCode' => 'required|string|max:10',
            'externalReference' => 'nullable|string|max:255',
            'notificationDisabled' => 'nullable|boolean',
            'additionalEmails' => 'nullable|string|max:255',
            'municipalInscription' => 'nullable|string|max:255',
            'stateInscription' => 'nullable|string|max:255',
            'observations' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'groupName' => 'nullable|string|max:255',
            'foreignCustomer' => 'nullable|boolean',
        ]);

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_TOKEN'),
            ])->put("{$this->baseUrl}/{$id}", $data);

            if ($response->successful()) {
                return response()->json($response->json(), 200);
            } else {
                return response()->json(['error' => 'Erro ao atualizar cliente'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método para deletar um cliente
    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'access_token' => env('ASAAS_API_TOKEN'),
            ])->delete("{$this->baseUrl}/{$id}");

            if ($response->successful()) {
                return response()->json(['message' => 'Cliente deletado com sucesso'], 204);
            } else {
                return response()->json(['error' => 'Erro ao deletar cliente'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
