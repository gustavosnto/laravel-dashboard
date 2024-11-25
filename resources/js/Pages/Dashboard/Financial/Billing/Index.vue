<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const billings = ref([]);
const loading = ref(true);

const statusTranslation = {
  PENDING: 'Pendente',
  RECEIVED: 'Recebido',
  CONFIRMED: 'Confirmado',
  OVERDUE: 'Vencido',
  REFUNDED: 'Reembolsado',
  RECEIVED_IN_CASH: 'Recebido em dinheiro',
  REFUND_REQUESTED: 'Reembolso solicitado',
  REFUND_IN_PROGRESS: 'Reembolso em progresso',
  CHARGEBACK_REQUESTED: 'Chargeback solicitado',
  CHARGEBACK_DISPUTE: 'Disputa de chargeback',
  AWAITING_CHARGEBACK_REVERSAL: 'Aguardando reversão de chargeback',
  DUNNING_REQUESTED: 'Cobrança solicitada',
  DUNNING_RECEIVED: 'Cobrança recebida',
  AWAITING_RISK_ANALYSIS: 'Aguardando análise de risco'
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('pt-BR');
};

const fetchBillings = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/financial/billing', {
            headers: {
                accept: 'application/json',
                access_token: import.meta.env.VITE_ASAAS_API_TOKEN,
            }
        });

        console.log('Resposta da API:', response.data);

        if (response.data && Array.isArray(response.data) && response.data.length > 0) {
            billings.value = response.data;
        } else {
            billings.value = [];
            console.error("Dados de cobrança não encontrados ou com formato incorreto.");
        }
    } catch (error) {
        console.error("Erro ao buscar cobranças:", error);
    } finally {
        loading.value = false;
    }
};

const deleteBilling = async (id) => {
    if (confirm('Tem certeza que deseja excluir esta cobrança?')) {
        try {
            await axios.delete(`/api/financial/billing/${id}`, {
                headers: {
                    accept: 'application/json',
                    access_token: import.meta.env.VITE_ASAAS_API_TOKEN,
                }
            });
            billings.value = billings.value.filter(billing => billing.id !== id);
        } catch (error) {
            console.error("Erro ao excluir cobrança:", error.response ? error.response.data : error);
        }
    }
};

onMounted(() => {
    fetchBillings();
});
</script>

<template>
    <Head title="Cobranças - Financeiro" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-white">Cobranças</h2>
                <a class="bg-main text-black px-4 py-2 rounded" href="/dashboard/financial/billing/create">Criar Cobrança</a>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-x-auto bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="loading" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
                            <div class="text-white">
                                Carregando...
                            </div>
                        </div>

                        <table class="min-w-full bg-white border border-gray-300 text-left">
                            <thead>
                                <tr>
                                    <th class="border-b p-2">Fatura</th>
                                    <th class="border-b p-2">Valor</th>
                                    <th class="border-b p-2">Vencimento</th>
                                    <th class="border-b p-2">Status</th>
                                    <th class="border-b p-2">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="billing in billings" :key="billing.id">
                                    <td class="border-b p-2">{{ billing.invoiceNumber }}</td>
                                    <td class="border-b p-2">{{ formatCurrency(billing.value) }}</td>
                                    <td class="border-b p-2">{{ formatDate(billing.dueDate) }}</td>
                                    <td class="border-b p-2">{{ statusTranslation[billing.status] || billing.status }}</td>
                                    <td class="border-b p-2 flex gap-3">
                                        <a :href="`/dashboard/financial/billing/${billing.id}`" class="flex items-center justify-center h-8 px-3 bg-main rounded">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a :href="`/dashboard/financial/billing/${billing.id}/edit`" class="flex items-center justify-center h-8 px-3 bg-main rounded">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button v-if="billing.status !== 'RECEIVED'" @click="deleteBilling(billing.id)" class="flex items-center justify-center h-8 px-3 bg-red-500 text-white rounded">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div v-if="billings.length === 0 && !loading" class="mt-4">
                            Não há cobranças registradas.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.fixed {
    z-index: 999;
}
</style>