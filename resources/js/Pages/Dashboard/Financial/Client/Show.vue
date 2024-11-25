<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    customer: Object,
});
</script>

<template>
    <Head title="Detalhes do Cliente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">Detalhes do Cliente</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold">ID: {{ customer.id }}</h3>
                        <div class="mt-4">
                            <h4 class="font-semibold">Nome:</h4>
                            <p>{{ customer.name }}</p>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold">Email:</h4>
                            <p>{{ customer.email }}</p>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold">Telefone:</h4>
                            <p>{{ customer.phone }}</p>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold">Celular:</h4>
                            <p>{{ customer.mobilePhone }}</p>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold">Endereço:</h4>
                            <p>{{ customer.address }} - {{ customer.addressNumber }}</p>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold">Complemento:</h4>
                            <p>{{ customer.complement }}</p>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold">Bairro:</h4>
                            <p>{{ customer.province }}</p>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold">CEP:</h4>
                            <p>{{ customer.postalCode }}</p>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold">Emails Adicionais:</h4>
                            <p>{{ customer.additionalEmails }}</p>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold">Empresa:</h4>
                            <p>{{ customer.company }}</p>
                        </div>
                        <div class="mt-4">
                            <h4 class="font-semibold">Status:</h4>
                            <p>{{ customer.deleted ? 'Inativo' : 'Ativo' }}</p>
                        </div>
                        <div class="mt-6">
                            <a class="bg-gray-500 text-white px-4 py-2 rounded" href="/dashboard/financial">Voltar para a lista de clientes</a>
                            <a :href="`/dashboard/financial/${customer.id}/edit`" class="bg-blue-500 text-white px-4 py-2 rounded">Editar Cliente</a>
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
