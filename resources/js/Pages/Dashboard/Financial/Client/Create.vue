<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const name = ref('');
const cpfCnpj = ref('');
const email = ref('');
const phone = ref('');
const mobilePhone = ref('');
const address = ref('');
const addressNumber = ref('');
const complement = ref('');
const province = ref('');
const postalCode = ref('');
const additionalEmails = ref('');
const company = ref('');

const maskCpfCnpj = (value) => {
    const cleanValue = value.replace(/\D/g, '');
    if (cleanValue.length <= 11) {
        return cleanValue.replace(/(\d{3})(\d)/, "$1.$2")
                         .replace(/(\d{3})(\d)/, "$1.$2")
                         .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    } else {
        return cleanValue.replace(/(\d{2})(\d)/, "$1.$2")
                         .replace(/(\d{3})(\d)/, "$1.$2")
                         .replace(/(\d{3})(\d{4})(\d)/, "$1.$2/$3")
                         .replace(/(\d{4})(\d)$/, "$1-$2");
    }
};

const maskPhone = (value) => {
    const cleanValue = value.replace(/\D/g, '');
    if (cleanValue.length <= 10) {
        return cleanValue.replace(/(\d{2})(\d)/, "($1) $2")
                         .replace(/(\d{4})(\d)/, "$1-$2");
    } else {
        return cleanValue.replace(/(\d{2})(\d)/, "($1) $2")
                         .replace(/(\d{5})(\d)/, "$1-$2");
    }
};

const maskPostalCode = (value) => {
    const cleanValue = value.replace(/\D/g, '');
    return cleanValue.replace(/(\d{5})(\d)/, "$1-$2");
};

const fetchAddressByCep = async (cep) => {
    if (cep.length === 8) {
        try {
            const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`);
            if (response.data && !response.data.erro) {
                address.value = response.data.logradouro;
                province.value = response.data.bairro;
            } else {
                console.error("CEP não encontrado");
            }
        } catch (error) {
            console.error("Erro ao buscar endereço:", error);
        }
    }
};

const clearMask = (value) => {
    return value.replace(/\D/g, '');
};

const submit = async () => {
    try {
        const cleanedPostalCode = clearMask(postalCode.value);

        await axios.post('/api/financial/customers', {
            name: name.value,
            cpfCnpj: cpfCnpj.value,
            email: email.value,
            phone: phone.value,
            mobilePhone: mobilePhone.value,
            address: address.value,
            addressNumber: addressNumber.value,
            complement: complement.value,
            province: province.value,
            postalCode: cleanedPostalCode,
            additionalEmails: additionalEmails.value,
            company: company.value,
        }, {
            headers: {
                accept: 'application/json',
                access_token: import.meta.env.VITE_ASAAS_API_TOKEN,
            }
        });
        window.location.href = '/dashboard/financial/client';
    } catch (error) {
        console.error("Erro ao criar cliente:", error.response ? error.response.data : error);
    }
};

const handlePostalCodeBlur = () => {
    postalCode.value = clearMask(postalCode.value);
    fetchAddressByCep(postalCode.value);
};
</script>

<template>
    <Head title="Criar Cliente" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">Criar Cliente</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                                    <input v-model="name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="cpfCnpj" class="block text-sm font-medium text-gray-700">CPF ou CNPJ</label>
                                    <input 
                                        v-model="cpfCnpj" 
                                        @input="cpfCnpj = maskCpfCnpj(cpfCnpj)" 
                                        type="text" 
                                        id="cpfCnpj" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                        required 
                                    />
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input v-model="email" type="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                                    <input 
                                        v-model="phone" 
                                        @input="phone = maskPhone(phone)" 
                                        type="text" 
                                        id="phone" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                        required 
                                    />
                                </div>
                                <div>
                                    <label for="mobilePhone" class="block text-sm font-medium text-gray-700">Celular</label>
                                    <input 
                                        v-model="mobilePhone" 
                                        @input="mobilePhone = maskPhone(mobilePhone)" 
                                        type="text" 
                                        id="mobilePhone" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                    />
                                </div>
                                <div>
                                    <label for="postalCode" class="block text-sm font-medium text-gray-700">CEP</label>
                                    <input 
                                        v-model="postalCode" 
                                        @input="postalCode = maskPostalCode(postalCode)" 
                                        @blur="handlePostalCodeBlur" 
                                        type="text" 
                                        id="postalCode" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                        required 
                                    />
                                </div>
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Endereço</label>
                                    <input v-model="address" type="text" id="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="addressNumber" class="block text-sm font-medium text-gray-700">Número</label>
                                    <input v-model="addressNumber" type="text" id="addressNumber" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required />
                                </div>
                                <div>
                                    <label for="complement" class="block text-sm font-medium text-gray-700">Complemento</label>
                                    <input v-model="complement" type="text" id="complement" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700">Bairro</label>
                                    <input v-model="province" type="text" id="province" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="additionalEmails" class="block text-sm font-medium text-gray-700">Emails Adicionais</label>
                                    <input v-model="additionalEmails" type="text" id="additionalEmails" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                                <div>
                                    <label for="company" class="block text-sm font-medium text-gray-700">Empresa</label>
                                    <input v-model="company" type="text" id="company" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="bg-main text-black px-4 py-2 rounded">Adicionar Cliente</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>
