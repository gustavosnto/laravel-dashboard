<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    businesses: Array,
});

const maskCNPJ = (cnpj) => {
    if (!cnpj) return '';
    return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
};

const deleteBusiness = async (id) => {
    if (confirm('Tem certeza que deseja apagar essa empresa?')) {
        Inertia.delete(`/dashboard/business/${id}`);
    }
};
</script>

<template>
    <Head title="Empresas" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-white">
                    Empresas
                </h2>
                
                <a class="bg-main text-black px-4 py-2 rounded" href="/dashboard/business/create">Adicionar Empresa</a>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-x-auto bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full bg-white border border-gray-300 text-left">
                            <thead>
                                <tr>
                                    <th class="border-b p-2">ID</th>
                                    <th class="border-b p-2">CNPJ</th>
                                    <th class="border-b p-2">Razão Social</th>
                                    <th class="border-b p-2">Cidade</th>
                                    <th class="border-b p-2">UF</th>
                                    <th class="border-b p-2">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="business in businesses" :key="business.id" class="whitespace-nowrap">
                                    <td class="border-b p-2">{{ business.id }}</td>
                                    <td class="border-b p-2">{{ business.cnpj ? maskCNPJ(business.cnpj) : '' }}</td>
                                    <td class="border-b p-2">{{ business.razao }}</td>
                                    <td class="border-b p-2">{{ business.cidade }}</td>
                                    <td class="border-b p-2">{{ business.uf }}</td>
                                    <td class="border-b p-2 flex gap-3">
                                        <a :href="`/dashboard/business/${business.id}`" class="flex items-center justify-center h-8 px-3 bg-main rounded"><i class="fa-solid fa-eye"></i></a>
                                        <a :href="`/dashboard/business/${business.id}/edit`" class="flex items-center justify-center h-8 px-3 bg-main rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <button @click="deleteBusiness(business.id)" class="flex items-center justify-center h-8 px-3 bg-main rounded"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Adicione estilos específicos para o componente aqui */
</style>
