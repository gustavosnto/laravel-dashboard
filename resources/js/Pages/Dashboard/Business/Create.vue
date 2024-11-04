<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/inertia-vue3';

const removeMask = (value) => value.replace(/\D/g, '');

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

const fetchAddress = async () => {
    const cleanCep = removeMask(form.cep);
    if (cleanCep.length === 8) {
        const response = await fetch(`https://viacep.com.br/ws/${cleanCep}/json/`);
        const data = await response.json();
        if (!data.erro) {
            form.endereco = data.logradouro;
            form.uf = data.uf;
            form.cidade = data.localidade;
        } else {
            alert('CEP não encontrado.');
            form.endereco = '';
            form.uf = '';
            form.cidade = '';
        }
    }
};

const submit = () => {
    form.cnpj = removeMask(form.cnpj);
    form.cep = removeMask(form.cep);
    form.telefone = removeMask(form.telefone);

    form.post('/dashboard/business');
};
</script>

<template>
    <Head title="Adicionar Empresa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">
                Adicionar Empresa
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
                                    <input v-model="form.cnpj" v-mask="'##.###.###/####-##'" type="text" id="cnpj" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="razao" class="block text-sm font-medium text-gray-700">Razão Social</label>
                                    <input v-model="form.razao" type="text" id="razao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="cep" class="block text-sm font-medium text-gray-700">CEP</label>
                                    <input v-model="form.cep" v-mask="'#####-###'" @blur="fetchAddress" type="text" id="cep" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
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
                                    <input v-model="form.telefone" v-mask="'(##) #####-####'" type="text" id="telefone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
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
