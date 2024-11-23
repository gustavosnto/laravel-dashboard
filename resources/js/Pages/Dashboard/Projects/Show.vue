<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    project: Object,
});

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('pt-BR');
};

</script>

<template>
    <Head title="Detalhes do Projeto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">
                Detalhes do Projeto
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>
                            <h3 class="text-lg font-bold">Nome do Projeto:</h3>
                            <p>{{ project.name }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Tecnologia:</h3>
                            <p>{{ project.technology }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Data de Início:</h3>
                            <p>{{ formatDate(project.start_date) }}</p> <!-- Aplicando formatação -->
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Data de Entrega:</h3>
                            <p>{{ formatDate(project.end_date) }}</p> <!-- Aplicando formatação -->
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Informações Adicionais:</h3>
                            <p>{{ project.additional_info }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold">Empresa:</h3>
                            <p>{{ project.business.razao }}</p>
                        </div>
                        <div class="mt-6">
                            <a class="bg-main text-black px-4 py-2 rounded" :href="`/dashboard/projects/${project.id}/edit`">Editar Projeto</a>
                            <a class="border-main border-2 text-black px-4 py-2 rounded ml-2" href="/dashboard/projects">Voltar para Projetos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
