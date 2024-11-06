<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    projects: Array,
});

const deleteProject = async (id) => {
    if (confirm('Are you sure you want to delete this project?')) {
        await this.$inertia.delete(`/projects/${id}`);
    }
};
</script>

<template>
    <Head title="Projetos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-white">
                    Projetos
                </h2>
                
                <a class="bg-main text-black px-4 py-2 rounded" href="/dashboard/projects/create">Adicionar Projeto</a>
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
                                    <th class="border-b p-2">Nome do Projeto</th>
                                    <th class="border-b p-2">Tecnologia</th>
                                    <th class="border-b p-2">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="projects.length > 0" v-for="project in projects" :key="project.id" class="whitespace-nowrap">
                                    <td class="border-b p-2">{{ project.id }}</td>
                                    <td class="border-b p-2">{{ project.name }}</td>
                                    <td class="border-b p-2">{{ project.technology }}</td>
                                    <td class="border-b p-2">
                                        <a :href="`/dashboard/projects/${project.id}`" class="text-blue-600">Ver</a>
                                        <a :href="`/dashboard/projects/${project.id}/edit`" class="text-green-600 ml-2">Editar</a>
                                        <button @click="deleteProject(project.id)" class="text-red-600 ml-2">Deletar</button>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td colspan="4" class="border-b p-2 text-center">Nenhum projeto encontrado.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
