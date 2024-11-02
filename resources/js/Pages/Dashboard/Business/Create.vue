<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    cnpj: '',
    razao: '',
    cep: '',
    endereco: '',
    numero: '',
    uf: '',
    cidade: '',
    email: '',
    telefone: '',
    nome_socio: '',
});

const submit = () => {
    form.post('/dashboard/business');
};
</script>

<template>
    <Head title="Create Business" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create New Business
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="cnpj" class="block text-sm font-medium text-gray-700">CNPJ</label>
                                    <input v-model="form.cnpj" type="text" id="cnpj" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="razao" class="block text-sm font-medium text-gray-700">Razão Social</label>
                                    <input v-model="form.razao" type="text" id="razao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="cep" class="block text-sm font-medium text-gray-700">CEP</label>
                                    <input v-model="form.cep" type="text" id="cep" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="endereco" class="block text-sm font-medium text-gray-700">Endereço</label>
                                    <input v-model="form.endereco" type="text" id="endereco" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="numero" class="block text-sm font-medium text-gray-700">Número</label>
                                    <input v-model="form.numero" type="text" id="numero" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="uf" class="block text-sm font-medium text-gray-700">UF</label>
                                    <input v-model="form.uf" type="text" id="uf" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required maxlength="2" />
                                </div>
                                <div>
                                    <label for="cidade" class="block text-sm font-medium text-gray-700">Cidade</label>
                                    <input v-model="form.cidade" type="text" id="cidade" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input v-model="form.email" type="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                                    <input v-model="form.telefone" type="text" id="telefone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="nome_socio" class="block text-sm font-medium text-gray-700">Nome do Sócio</label>
                                    <input v-model="form.nome_socio" type="text" id="nome_socio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Business</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Adicione estilos específicos para o componente aqui */
</style>
