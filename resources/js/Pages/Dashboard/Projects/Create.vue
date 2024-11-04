<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import { defineProps } from 'vue';

const props = defineProps({
    businesses: Array,
});

const form = useForm({
    business_id: '',
    name: '',
    technology: '',
    start_date: '',
    end_date: '',
    additional_info: '',
});

const submit = () => {
    form.post('/dashboard/projects');
};
</script>

<template>
    <Head title="Create Project" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">
                Adicionar Novo Projeto
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="business_id" class="block text-sm font-medium text-gray-700">Empresa</label>
                                    <select v-model="form.business_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="" disabled>Selecione uma empresa</option>
                                        <option v-for="business in businesses" :key="business.id" :value="business.id">{{ business.razao }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nome do Projeto</label>
                                    <input v-model="form.name" type="text" id="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="technology" class="block text-sm font-medium text-gray-700">Tecnologia</label>
                                    <input v-model="form.technology" type="text" id="technology" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Data de Início</label>
                                    <input v-model="form.start_date" type="date" id="start_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">Data de Entrega</label>
                                    <input v-model="form.end_date" type="date" id="end_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="additional_info" class="block text-sm font-medium text-gray-700">Informações Adicionais</label>
                                    <textarea v-model="form.additional_info" id="additional_info" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Criar Projeto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
