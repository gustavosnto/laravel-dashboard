<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const totalClients = ref(0);
const totalBillings = ref(0);
const loading = ref(true);

const fetchFinancialData = async () => {
    loading.value = true;
    try {
        const clientsResponse = await axios.get('/api/financial/customers');
        totalClients.value = clientsResponse.data.data.length;

        const billingsResponse = await axios.get('/api/financial/billing', {
            headers: {
                accept: 'application/json',
                access_token: import.meta.env.VITE_ASAAS_API_TOKEN,
            }
        });

        console.log('Resposta de Cobranças:', billingsResponse.data);
        
        if (billingsResponse.data && Array.isArray(billingsResponse.data)) {
            totalBillings.value = billingsResponse.data.length;
        } else {
            totalBillings.value = 0;
        }
    } catch (error) {
        console.error("Erro ao buscar dados financeiros:", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchFinancialData();
});
</script>

<template>
    <Head title="Financeiro" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">Visão Geral do Financeiro</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="loading" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
                            <div class="text-white">Carregando...</div>
                        </div>

                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="w-full h-32 bg-main/50 flex flex-col items-center justify-center rounded-lg">
                                <p class="text-3xl font-bold">{{ totalClients }}</p>
                                <div>Clientes</div>
                            </div>
                            <div class="w-full h-32 bg-main/50 flex flex-col items-center justify-center rounded-lg">
                                <p class="text-3xl font-bold">{{ totalBillings }}</p>
                                <div>Cobranças</div>
                            </div>
                            <div class="w-full h-32 bg-main/50 flex flex-col items-center justify-center rounded-lg">
                                <p class="text-3xl font-bold">...</p>
                                <div>Outras informações</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-bold">Direções rápidas</h3>
                    <div class="grid md:grid-cols-2 gap-6 mt-4">
                        <a href="/dashboard/financial/client" class="bg-gray-500 text-white p-4 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-users mr-2"></i>
                            Gerenciar Clientes
                        </a>
                        <a href="/dashboard/financial/billing" class="bg-gray-500 text-white p-4 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-file-invoice mr-2"></i>
                            Gerenciar Cobranças
                        </a>
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