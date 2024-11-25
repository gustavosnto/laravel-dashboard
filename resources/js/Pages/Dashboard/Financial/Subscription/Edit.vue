<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const router = useRouter(); // Usando router para redirecionar após a edição
const form = useForm({
  customer: '',
  value: '',
  dueDate: '',
  description: '',
  billingType: 'BOLETO',
  cycle: 'MONTHLY',
});

const subscriptionId = ref(null);
const customerId = ref(null);  // Variável para armazenar o ID do cliente
const subscription = ref({});
const customer = ref(null);  // Variável para armazenar o nome do cliente
const loading = ref(true);

onMounted(() => {
  // Acessando o objeto "data-page" diretamente do DOM
  const pageData = document.getElementById('app').dataset.page;
  
  // Convertendo o JSON stringificado de data-page para objeto
  const parsedData = JSON.parse(pageData);
  
  // Acessando o ID da assinatura e o ID do cliente através de props.billing.id
  subscriptionId.value = parsedData.props.billing.id;
  customerId.value = parsedData.props.billing.customer;
  
  console.log("ID da assinatura:", subscriptionId.value);  // Verificando no console
  console.log("ID do cliente:", customerId.value);  // Verificando no console
  
  // Buscando os dados da assinatura e cliente
  fetchSubscription();
  fetchCustomer();
});

// Função para buscar os dados da assinatura
const fetchSubscription = async () => {
  loading.value = true;
  try {
    const response = await axios.get(`/api/financial/subscription/${subscriptionId.value}`, {
      headers: {
        accept: 'application/json',
        access_token: import.meta.env.VITE_ASAAS_API_TOKEN,
      }
    });
    subscription.value = response.data;
    
    // Preenchendo o formulário com os dados da assinatura
    form.value = subscription.value.value;
    form.dueDate = subscription.value.nextDueDate;
    form.description = subscription.value.description;
    form.billingType = subscription.value.billingType;
    form.cycle = subscription.value.cycle;
  } catch (error) {
    console.error("Erro ao buscar assinatura:", error);
  } finally {
    loading.value = false;
  }
};

// Função para buscar os dados do cliente
const fetchCustomer = async () => {
  try {
    const response = await axios.get(`/api/financial/customers/${customerId.value}`, {
      headers: {
        accept: 'application/json',
        access_token: import.meta.env.VITE_ASAAS_API_TOKEN,
      }
    });
    customer.value = response.data.name; // Obtendo o nome do cliente
    form.customer = customer.value; // Preenchendo o formulário com o nome do cliente
  } catch (error) {
    console.error("Erro ao buscar cliente:", error);
  }
};

// Função para enviar o formulário
const submitForm = () => {
  form.put(`/api/financial/subscription/${subscriptionId.value}`, {
    onSuccess: () => {
      router.push(`/dashboard/financial/subscription/${subscriptionId.value}`);
    },
    onError: () => {
      console.error('Erro ao atualizar assinatura');
    },
  });
};
</script>

<template>
  <Head title="Editar Assinatura - Financeiro" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-white">Editar Assinatura</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div v-if="loading" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
              <div class="text-white">
                Carregando...
              </div>
            </div>

            <form @submit.prevent="submitForm" v-if="!loading && subscription">
              <div class="grid grid-cols-1 gap-4">
                <!-- Cliente -->
                <div>
                  <label for="customer" class="block text-sm font-medium text-gray-700">Cliente</label>
                  <input
                    v-model="form.customer"
                    type="text"
                    id="customer"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed"
                    readonly
                    style="pointer-events: none; outline: none;"
                  />
                </div>

                <!-- Valor -->
                <div>
                  <label for="value" class="block text-sm font-medium text-gray-700">Valor</label>
                  <input
                    v-model="form.value"
                    type="number"
                    id="value"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500"
                    required
                  />
                </div>

                <!-- Data de Vencimento -->
                <div>
                  <label for="dueDate" class="block text-sm font-medium text-gray-700">Data de Vencimento</label>
                  <input
                    v-model="form.dueDate"
                    type="date"
                    id="dueDate"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500"
                    required
                  />
                </div>

                <!-- Descrição -->
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                  <input
                    v-model="form.description"
                    type="text"
                    id="description"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500"
                  />
                </div>

                <!-- Tipo de Cobrança -->
                <div>
                  <label for="billingType" class="block text-sm font-medium text-gray-700">Tipo de Cobrança</label>
                  <select
                    v-model="form.billingType"
                    id="billingType"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500"
                    required
                  >
                    <option value="BOLETO">Boleto</option>
                    <option value="PIX">PIX</option>
                  </select>
                </div>

                <!-- Ciclo -->
                <div>
                  <label for="cycle" class="block text-sm font-medium text-gray-700">Ciclo</label>
                  <select
                    v-model="form.cycle"
                    id="cycle"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500"
                    required
                  >
                    <option value="MONTHLY">Mensal</option>
                    <option value="YEARLY">Anual</option>
                  </select>
                </div>
              </div>

              <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Salvar Alterações</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.fixed {
  z-index: 999;
}
</style>