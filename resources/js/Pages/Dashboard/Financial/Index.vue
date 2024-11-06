<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const customers = ref([]);
const loading = ref(true); // Variável para controlar o estado de carregamento

const fetchCustomers = async () => {
    loading.value = true; // Começa o carregamento
    try {
        const response = await axios.get('/api/financial/customers', {});
        customers.value = response.data.data;
    } catch (error) {
        console.error("Erro ao buscar clientes:", error);
    } finally {
        loading.value = false; // Finaliza o carregamento
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
            // Atualiza a lista de clientes após a exclusão
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
                
                <a class="bg-main text-black px-4 py-2 rounded" href="/dashboard/financial/create">Adicionar Cliente</a>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold">Lista de Clientes</h3>
                        
                        <!-- Popup de carregamento -->
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
                                    <td class="border-b p-2">
                                        <a :href="`/dashboard/financial/${customer.id}`">Ver</a>
                                        <button @click="deleteCustomer(customer.id)" class="text-red-600 ml-2">Excluir</button>
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
/* Adicione estilos específicos para o componente aqui */
.fixed {
    z-index: 999; /* Garante que o popup esteja acima de outros elementos */
}
</style>
