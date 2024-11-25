<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class FinancialSubscriptionController extends Controller
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
                return Inertia::render('Dashboard/Financial/Subscription/Index', [
                    'billings' => $response->json()['data'],
                ]);
            } else {
                return redirect()->route('financial.index')->withErrors(['message' => 'Erro ao carregar cobranças: ' . $response->json()['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->route('financial.index')->withErrors(['message' => 'Erro ao carregar cobranças: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        return Inertia::render('Dashboard/Financial/Subscription/Create');
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

            if ($response->successful()) {
                return redirect()->route('financial.index')->with('success', 'Cobrança criada com sucesso!');
            } else {
                return redirect()->route('financial.create')->withErrors(['message' => 'Erro ao criar cobrança: ' . $response->json()['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->route('financial.create')->withErrors(['message' => 'Erro ao criar cobrança: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->get("{$this->baseUrl}/{$id}");

        if ($response->successful()) {
            return Inertia::render('Dashboard/Financial/Subscription/Show', [
                'billing' => $response->json(),
            ]);
        } else {
            return redirect()->route('financial.index')->withErrors(['message' => 'Cobrança não encontrada']);
        }
    }

    public function edit($id)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'access_token' => env('ASAAS_API_TOKEN'),
        ])->get("{$this->baseUrl}/{$id}");

        if ($response->successful()) {
            return Inertia::render('Dashboard/Financial/Subscription/Edit', [
                'billing' => $response->json(),
            ]);
        } else {
            return redirect()->route('financial.index')->withErrors(['message' => 'Cobrança não encontrada']);
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
                return redirect()->route('financial.index')->with('success', 'Cobrança atualizada com sucesso!');
            } else {
                return redirect()->route('financial.edit', $id)->withErrors(['message' => 'Erro ao atualizar cobrança: ' . $response->json()['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->route('financial.edit', $id)->withErrors(['message' => 'Erro ao atualizar cobrança: ' . $e->getMessage()]);
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
                return redirect()->route('financial.index')->with('success', 'Cobrança deletada com sucesso!');
            } else {
                return redirect()->route('financial.index')->withErrors(['message' => 'Erro ao deletar cobrança: ' . $response->json()['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->route('financial.index')->withErrors(['message' => 'Erro ao deletar cobrança: ' . $e->getMessage()]);
        }
    }
}
