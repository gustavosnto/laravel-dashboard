<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const form = useForm({
    customer: '',
    value: '',
    dueDate: '',
    description: '',
    billingType: 'BOLETO',
    externalReference: '',
    discount: {
        value: null,
        type: 'PERCENTAGE',
        dueDateLimitDays: 0,
    },
    interest: {
        value: null,
    },
    fine: {
        value: null,
        type: 'FIXED',
    },
});

const submit = () => {
    form.post('/dashboard/financial/billing', {
        onSuccess: () => {
            form.reset();
        },
    });
};
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
                                <!-- Campos do formulário de criação -->
                                <div>
                                    <label for="customer" class="block text-sm font-medium text-gray-700">ID do Cliente</label>
                                    <input v-model="form.customer" type="text" id="customer" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" required />
                                </div>
                                <div>
                                    <label for="value" class="block text-sm font-medium text-gray-700">Valor</label>
                                    <input v-model="form.value" type="number" id="value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" required />
                                </div>
                                <div>
                                    <label for="dueDate" class="block text-sm font-medium text-gray-700">Data de Vencimento</label>
                                    <input v-model="form.dueDate" type="date" id="dueDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" required />
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                                    <input v-model="form.description" type="text" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" />
                                </div>
                                <div>
                                    <label for="externalReference" class="block text-sm font-medium text-gray-700">Referência Externa</label>
                                    <input v-model="form.externalReference" type="text" id="externalReference" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500" />
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