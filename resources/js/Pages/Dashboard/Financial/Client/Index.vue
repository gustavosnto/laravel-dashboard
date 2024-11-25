<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const customers = ref([]);
const loading = ref(true);

const fetchCustomers = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/financial/customers', {});
        customers.value = response.data.data;
    } catch (error) {
        console.error("Erro ao buscar clientes:", error);
    } finally {
        loading.value = false;
    }
};

const deleteCustomer = async (id) => {
    if (confirm('Tem certeza que deseja excluir este cliente?')) {
        try {
            await axios.delete(`/api/financial/customers/${id}`, {
                headers: {
                    accept: 'application/json',
                    access_token: import.meta.env.VITE_ASAAS_API_TOKEN,
                }
            });
            customers.value = customers.value.filter(customer => customer.id !== id);
        } catch (error) {
            console.error("Erro ao excluir cliente:", error.response ? error.response.data : error);
        }
    }
};

onMounted(() => {
    fetchCustomers();
});
</script>

<template>
    <Head title="Financeiro" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-white">Financeiro</h2>
                
                <a class="bg-main text-black px-4 py-2 rounded" href="/dashboard/financial/client/create">Adicionar Cliente</a>
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
                                    <th class="border-b p-2">Nome</th>
                                    <th class="border-b p-2">Email</th>
                                    <th class="border-b p-2">Telefone</th>
                                    <th class="border-b p-2">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="customer in customers" :key="customer.id">
                                    <td class="border-b p-2">{{ customer.name }}</td>
                                    <td class="border-b p-2">{{ customer.email }}</td>
                                    <td class="border-b p-2">{{ customer.phone }}</td>
                                    <td class="border-b p-2 flex gap-3">
                                        <a :href="`/dashboard/financial/client/${customer.id}`" class="flex items-center justify-center h-8 px-4 bg-main text-black rounded gap-2">
                                            <i class="fa-solid fa-eye"></i> Ver
                                        </a>
                                        <button @click="deleteCustomer(customer.id)" class="flex items-center justify-center h-8 px-3 bg-red-500 text-white rounded gap-2">
                                            <i class="fa-solid fa-trash"></i> Excluir
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="customers.length === 0 && !loading" class="mt-4">Não há clientes cadastrados.</div>
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