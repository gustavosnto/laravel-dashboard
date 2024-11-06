<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios'; // Importando o Axios

// Criando referências para os campos do formulário
const name = ref('');
const cpfCnpj = ref('');
const email = ref('');
const phone = ref('');
const mobilePhone = ref('');
const address = ref('');
const addressNumber = ref('');
const complement = ref('');
const province = ref(''); // Usado para bairro
const postalCode = ref('');
const additionalEmails = ref('');
const company = ref('');

// Função para aplicar a máscara de CPF/CNPJ
const maskCpfCnpj = (value) => {
    const cleanValue = value.replace(/\D/g, ''); // Remove caracteres não numéricos
    if (cleanValue.length <= 11) {
        // CPF
        return cleanValue.replace(/(\d{3})(\d)/, "$1.$2")
                         .replace(/(\d{3})(\d)/, "$1.$2")
                         .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    } else {
        // CNPJ
        return cleanValue.replace(/(\d{2})(\d)/, "$1.$2")
                         .replace(/(\d{3})(\d)/, "$1.$2")
                         .replace(/(\d{3})(\d{4})(\d)/, "$1.$2/$3")
                         .replace(/(\d{4})(\d)$/, "$1-$2");
    }
};

// Função para aplicar a máscara de telefone
const maskPhone = (value) => {
    const cleanValue = value.replace(/\D/g, ''); // Remove caracteres não numéricos
    if (cleanValue.length <= 10) {
        // Telefone
        return cleanValue.replace(/(\d{2})(\d)/, "($1) $2")
                         .replace(/(\d{4})(\d)/, "$1-$2");
    } else {
        // Celular
        return cleanValue.replace(/(\d{2})(\d)/, "($1) $2")
                         .replace(/(\d{5})(\d)/, "$1-$2");
    }
};

// Função para aplicar a máscara de CEP
const maskPostalCode = (value) => {
    const cleanValue = value.replace(/\D/g, ''); // Remove caracteres não numéricos
    return cleanValue.replace(/(\d{5})(\d)/, "$1-$2"); // Formato: 00000-000
};

// Função para buscar dados de endereço através do CEP
const fetchAddressByCep = async (cep) => {
    if (cep.length === 8) { // Verifica se o CEP está completo
        try {
            const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`);
            if (response.data && !response.data.erro) {
                // Preenche os campos de endereço e bairro
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

// Função para limpar a máscara antes de enviar
const clearMask = (value) => {
    return value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
};

const submit = async () => {
    try {
        // Limpa a máscara do CEP antes de enviar
        const cleanedPostalCode = clearMask(postalCode.value);

        // Enviando os dados do novo cliente para a API
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
            postalCode: cleanedPostalCode, // Usa o CEP limpo
            additionalEmails: additionalEmails.value,
            company: company.value,
        }, {
            headers: {
                accept: 'application/json',
                access_token: import.meta.env.VITE_ASAAS_API_TOKEN, // Adicione seu token aqui
            }
        });
        // Redirecionar ou mostrar uma mensagem de sucesso
        window.location.href = '/dashboard/financial'; // Redireciona para a lista de clientes após a criação
    } catch (error) {
        console.error("Erro ao criar cliente:", error.response ? error.response.data : error);
    }
};

// Evento de blur para buscar endereço ao sair do campo de CEP
const handlePostalCodeBlur = () => {
    postalCode.value = clearMask(postalCode.value); // Limpa a máscara do CEP
    fetchAddressByCep(postalCode.value); // Chama a função para buscar o endereço
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
                            <div class="grid grid-cols-1 gap-4">
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
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Adicionar Cliente</button>
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
