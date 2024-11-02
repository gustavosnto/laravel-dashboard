<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    business: Object,
});
</script>

<template>
    <Head title="Business Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Business Details
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>
                            <h3 class="text-lg font-bold">CNPJ:</h3>
                            <p>{{ business.cnpj }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Razão Social:</h3>
                            <p>{{ business.razao }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Endereço:</h3>
                            <p>{{ business.endereco }}, {{ business.numero }}</p>
                            <p>{{ business.cidade }} - {{ business.uf }}</p>
                            <p>CEP: {{ business.cep }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Contato:</h3>
                            <p>Email: {{ business.email }}</p>
                            <p>Telefone: {{ business.telefone }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Nome do Sócio:</h3>
                            <p>{{ business.nome_socio }}</p>
                        </div>
                        <div class="mt-6">
                            <a class="bg-blue-500 text-white px-4 py-2 rounded" :href="`/dashboard/business/${business.id}/edit`">Edit Business</a>
                            <a class="bg-gray-500 text-white px-4 py-2 rounded ml-2" href="/dashboard/business">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Adicione estilos específicos para o componente aqui */
</style>
