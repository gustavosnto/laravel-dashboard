<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios'; // Importando o Axios
import { useForm } from '@inertiajs/inertia-vue3';
import { defineProps } from 'vue';

const props = defineProps({
    customer: Object,
});

// Criando o formulário usando a biblioteca Inertia
const form = useForm({
    id: props.customer.id,
    name: props.customer.name,
    cpfCnpj: props.customer.cpfCnpj,
    email: props.customer.email,
    phone: props.customer.phone,
    mobilePhone: props.customer.mobilePhone,
    address: props.customer.address,
    addressNumber: props.customer.addressNumber,
    complement: props.customer.complement,
    province: props.customer.province,
    postalCode: props.customer.postalCode,
    additionalEmails: props.customer.additionalEmails,
    company: props.customer.company,
});

// Função de envio
const submit = async () => {
    try {
        await axios.put(`/api/financial/customers/${form.id}`, {
            name: form.name,
            cpfCnpj: form.cpfCnpj,
            email: form.email,
            phone: form.phone,
            mobilePhone: form.mobilePhone,
            address: form.address,
            addressNumber: form.addressNumber,
            complement: form.complement,
            province: form.province,
            postalCode: form.postalCode,
            additionalEmails: form.additionalEmails,
            company: form.company,
        }, {
            headers: {
                accept: 'application/json',
                access_token: import.meta.env.VITE_ASAAS_API_TOKEN, // Adicione seu token aqui
            }
        });
        // Redirecionar ou mostrar uma mensagem de sucesso
        window.location.href = '/dashboard/financial'; // Redireciona para a lista de clientes após a atualização
    } catch (error) {
        console.error("Erro ao atualizar cliente:", error.response ? error.response.data : error);
    }
};
</script>

<template>
    <Head title="Editar Cliente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">Editar Cliente</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                                    <input v-model="form.name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="cpfCnpj" class="block text-sm font-medium text-gray-700">CPF ou CNPJ</label>
                                    <input v-model="form.cpfCnpj" type="text" id="cpfCnpj" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input v-model="form.email" type="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                                    <input v-model="form.phone" type="text" id="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="mobilePhone" class="block text-sm font-medium text-gray-700">Celular</label>
                                    <input v-model="form.mobilePhone" type="text" id="mobilePhone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Endereço</label>
                                    <input v-model="form.address" type="text" id="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="addressNumber" class="block text-sm font-medium text-gray-700">Número</label>
                                    <input v-model="form.addressNumber" type="text" id="addressNumber" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="complement" class="block text-sm font-medium text-gray-700">Complemento</label>
                                    <input v-model="form.complement" type="text" id="complement" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700">Bairro</label>
                                    <input v-model="form.province" type="text" id="province" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="postalCode" class="block text-sm font-medium text-gray-700">CEP</label>
                                    <input v-model="form.postalCode" type="text" id="postalCode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="additionalEmails" class="block text-sm font-medium text-gray-700">Emails Adicionais</label>
                                    <input v-model="form.additionalEmails" type="text" id="additionalEmails" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="company" class="block text-sm font-medium text-gray-700">Empresa</label>
                                    <input v-model="form.company" type="text" id="company" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Atualizar Cliente</button>
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
