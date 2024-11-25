<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const form = useForm({
    customer: '',
    value: '',
    dueDate: '',
    description: '',
    billingType: 'BOLETO',
    externalReference: '',
    discount: {
        value: 0,
        type: 'PERCENTAGE',
        dueDateLimitDays: 0,
    },
    interest: {
        value: 1,
    },
    fine: {
        value: 2,
        type: 'FIXED',
    },
});

// Lista de clientes
const customers = ref([]);

const fetchCustomers = async () => {
    try {
        const response = await axios.get('/api/financial/customers', {
            headers: {
                accept: 'application/json',
                access_token: import.meta.env.VITE_ASAAS_API_TOKEN, // Adicione o token correto
            }
        });
        if (response.data && Array.isArray(response.data.data)) {
            customers.value = response.data.data;
        }
    } catch (error) {
        console.error("Erro ao buscar clientes:", error);
    }
};

const submit = () => {
    form.post('/api/financial/billing', {
        onSuccess: () => {
            window.location.href = '/dashboard/financial/billing';
        },
        onError: () => {
            console.error('Erro ao criar cobrança');
        }
    });
};

// Carregar os clientes ao montar o componente
onMounted(() => {
    fetchCustomers();
});
</script>

<template>
    <Head title="Criar Cobrança" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">Criar Cobrança</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-4">
                                <!-- Seleção do cliente -->
                                <div>
                                    <label for="customer" class="block text-sm font-medium text-gray-700">Cliente</label>
                                    <select v-model="form.customer" id="customer" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" required>
                                        <option disabled value="">Selecione um cliente</option>
                                        <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                            {{ customer.name }}
                                        </option>
                                    </select>
                                </div>
                                
                                <!-- Valor -->
                                <div>
                                    <label for="value" class="block text-sm font-medium text-gray-700">Valor</label>
                                    <input v-model="form.value" type="number" id="value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" required />
                                </div>
                                
                                <!-- Data de Vencimento -->
                                <div>
                                    <label for="dueDate" class="block text-sm font-medium text-gray-700">Data de Vencimento</label>
                                    <input v-model="form.dueDate" type="date" id="dueDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" required />
                                </div>
                                
                                <!-- Descrição -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                                    <input v-model="form.description" type="text" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" />
                                </div>
                                
                                <!-- Referência Externa -->
                                <div>
                                    <label for="externalReference" class="block text-sm font-medium text-gray-700">Referência Externa</label>
                                    <input v-model="form.externalReference" type="text" id="externalReference" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" />
                                </div>

                                <!-- Tipo de Cobrança -->
                                <div>
                                    <label for="billingType" class="block text-sm font-medium text-gray-700">Tipo de Cobrança</label>
                                    <select v-model="form.billingType" id="billingType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" required>
                                        <option value="BOLETO">Boleto</option>
                                        <option value="PIX">PIX</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Criar Cobrança</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Estilos específicos do componente */
</style>