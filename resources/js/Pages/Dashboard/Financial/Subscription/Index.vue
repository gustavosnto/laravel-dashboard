<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const subscriptions = ref([]);
const loading = ref(true);

const statusTranslation = {
  ACTIVE: 'Ativa',
  INACTIVE: 'Inativa',
  CANCELED: 'Cancelada',
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

const fetchSubscriptions = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/financial/subscription', {
            headers: {
                accept: 'application/json',
                access_token: import.meta.env.VITE_ASAAS_API_TOKEN,
            }
        });

        console.log('Resposta da API:', response.data);

        // Verifique se a resposta contém a chave 'data' e se é um array não vazio
        if (response.data && Array.isArray(response.data) && response.data.length > 0) {
            subscriptions.value = response.data; // Agora estamos recebendo as subscriptions diretamente
        } else {
            subscriptions.value = [];
            console.error("Dados de assinatura não encontrados ou com formato incorreto.");
        }
    } catch (error) {
        console.error("Erro ao buscar assinaturas:", error);
    } finally {
        loading.value = false;
    }
};

const deleteSubscription = async (id) => {
    if (confirm('Tem certeza que deseja excluir esta assinatura?')) {
        try {
            await axios.delete(`/api/financial/subscription/${id}`, {
                headers: {
                    accept: 'application/json',
                    access_token: import.meta.env.VITE_ASAAS_API_TOKEN,
                }
            });
            subscriptions.value = subscriptions.value.filter(subscription => subscription.id !== id);
        } catch (error) {
            console.error("Erro ao excluir assinatura:", error.response ? error.response.data : error);
        }
    }
};

onMounted(() => {
    fetchSubscriptions();
});
</script>

<template>
    <Head title="Assinaturas - Financeiro" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-white">Assinaturas</h2>
                <a class="bg-main text-black px-4 py-2 rounded" href="/dashboard/financial/subscription/create">Criar Assinatura</a>
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
                                    <th class="border-b p-2">Descrição</th>
                                    <th class="border-b p-2">Valor</th>
                                    <th class="border-b p-2">Próximo Vencimento</th>
                                    <th class="border-b p-2">Ciclo</th>
                                    <th class="border-b p-2">Status</th>
                                    <th class="border-b p-2">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="subscription in subscriptions" :key="subscription.id">
                                    <td class="border-b p-2">{{ subscription.description || 'N/A' }}</td>
                                    <td class="border-b p-2">{{ formatCurrency(subscription.value) }}</td>
                                    <td class="border-b p-2">{{ formatDate(subscription.nextDueDate) }}</td>
                                    <td class="border-b p-2">{{ subscription.cycle }}</td>
                                    <td class="border-b p-2">{{ statusTranslation[subscription.status] || subscription.status }}</td>
                                    <td class="border-b p-2 flex gap-3">
                                        <a :href="`/dashboard/financial/subscription/${subscription.id}`" class="flex items-center justify-center h-8 px-3 bg-main rounded">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a :href="`/dashboard/financial/subscription/${subscription.id}/edit`" class="flex items-center justify-center h-8 px-3 bg-main rounded">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button v-if="subscription.status !== 'CANCELED'" @click="deleteSubscription(subscription.id)" class="flex items-center justify-center h-8 px-3 bg-red-500 text-white rounded">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div v-if="subscriptions.length === 0 && !loading" class="mt-4">
                            Não há assinaturas registradas.
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