<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    business: Object,
});

const formatCNPJ = (cnpj) => {
    if (!cnpj) return '';
    return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
};

const formatPhone = (phone) => {
    if (!phone) return '';
    return phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
};

const formatCEP = (cep) => {
    if (!cep) return '';
    return cep.replace(/(\d{5})(\d{3})/, '$1-$2');
};
</script>

<template>
    <Head title="Detalhes da Empresa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">
                Detalhes
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>
                            <h3 class="text-lg font-bold">CNPJ:</h3>
                            <p>{{ formatCNPJ(business.cnpj) }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Razão Social:</h3>
                            <p>{{ business.razao }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Endereço:</h3>
                            <p>{{ business.endereco }}, {{ business.numero }}</p>
                            <p>{{ business.cidade }} - {{ business.uf }}</p>
                            <p>CEP: {{ formatCEP(business.cep) }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Contato:</h3>
                            <p>Email: {{ business.email }}</p>
                            <p>Telefone: {{ formatPhone(business.telefone) }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Nome do Sócio:</h3>
                            <p>{{ business.nome_socio }}</p>
                        </div>
                        <div class="mt-6">
                            <a class="bg-main text-black px-4 py-2 rounded" :href="`/dashboard/business/${business.id}/edit`">Editar empresa</a>
                            <a class="border-main border-2 text-black px-4 py-2 rounded ml-2" href="/dashboard/business">Voltar para listagem</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
