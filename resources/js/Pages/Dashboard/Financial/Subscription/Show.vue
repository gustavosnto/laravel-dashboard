<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

const subscriptionId = ref(null);
const subscription = ref({});
const loading = ref(true);

// Acessando o objeto "data-page" diretamente do DOM e extraindo o ID da assinatura
onMounted(() => {
  const pageData = document.getElementById('app').dataset.page;
  const parsedData = JSON.parse(pageData);
  subscriptionId.value = parsedData.props.billing.id;  // Obtendo o ID da assinatura

  // Chamando a API para buscar dados da assinatura
  fetchSubscription(subscriptionId.value);
});

// Função para buscar dados da assinatura com base no ID
const fetchSubscription = async (subscriptionId) => {
  loading.value = true;
  try {
    const response = await axios.get(`/api/financial/subscription/${subscriptionId}`, {
      headers: {
        accept: 'application/json',
        access_token: import.meta.env.VITE_ASAAS_API_TOKEN,
      }
    });
    subscription.value = response.data;
  } catch (error) {
    console.error("Erro ao buscar assinatura:", error);
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <Head title="Detalhes da Assinatura - Financeiro" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-white">Detalhes da Assinatura</h2>
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

            <div v-if="!loading && subscription">
              <div class="mb-6">
                <p class="font-semibold text-lg">ID da Assinatura</p>
                <p>{{ subscription.id }}</p>
              </div>

              <div class="mb-6">
                <p class="font-semibold text-lg">Cliente</p>
                <p>{{ subscription.customer }}</p>
              </div>

              <div class="mb-6">
                <p class="font-semibold text-lg">Descrição</p>
                <p>{{ subscription.description }}</p>
              </div>

              <div class="mb-6">
                <p class="font-semibold text-lg">Valor</p>
                <p>{{ new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(subscription.value) }}</p>
              </div>

              <div class="mb-6">
                <p class="font-semibold text-lg">Próximo Vencimento</p>
                <p>{{ new Date(subscription.nextDueDate).toLocaleDateString('pt-BR') }}</p>
              </div>

              <div class="mb-6">
                <p class="font-semibold text-lg">Ciclo</p>
                <p>{{ subscription.cycle }}</p>
              </div>

              <div class="mb-6">
                <p class="font-semibold text-lg">Status</p>
                <p>{{ subscription.status }}</p>
              </div>

              <div class="mb-6">
                <p class="font-semibold text-lg">Tipo de Cobrança</p>
                <p>{{ subscription.billingType }}</p>
              </div>

              <div class="mt-6">
                <a :href="`/dashboard/financial/subscription/${subscription.id}/edit`" class="inline-block px-4 py-2 bg-blue-500 text-white rounded">Editar</a>
                <a href="/dashboard/financial/subscription" class="inline-block px-4 py-2 bg-gray-500 text-white rounded ml-4">Voltar</a>
              </div>
            </div>
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