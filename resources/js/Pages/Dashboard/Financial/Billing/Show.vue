<script setup>
import { onMounted, ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    billing: Object,
});

const deleteBilling = async () => {
    if (confirm('Tem certeza que deseja excluir esta cobrança?')) {
        await axios.delete(`/dashboard/financial/billing/${props.billing.id}`);
        // Após excluir, redireciona para a lista de cobranças
        window.location.href = '/dashboard/financial/billing';
    }
};

onMounted(() => {
    // Aqui você pode carregar qualquer dado adicional
});
</script>

<template>
    <Head title="Detalhes da Cobrança" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">Detalhes da Cobrança</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div>
                            <p><strong>ID:</strong> {{ billing.id }}</p>
                            <p><strong>Cliente:</strong> {{ billing.customer }}</p>
                            <p><strong>Valor:</strong> {{ billing.value }}</p>
                            <p><strong>Vencimento:</strong> {{ billing.dueDate }}</p>
                            <p><strong>Descrição:</strong> {{ billing.description }}</p>
                            <p><strong>Referência Externa:</strong> {{ billing.externalReference }}</p>
                        </div>
                        <div class="mt-4">
                            <button @click="deleteBilling" class="bg-red-500 text-white px-4 py-2 rounded">Excluir Cobrança</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Estilos específicos do componente */
</style>