<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    projectCount: Number,
    businessCount: Number,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-white"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 grid gap-6">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="w-full h-32 bg-main/50 flex flex-col items-center justify-center rounded-lg">
                                <p class="text-3xl font-bold">{{ props.projectCount }}</p>
                                <div>Projetos</div>
                            </div>
                            <div class="w-full h-32 bg-main/50 flex flex-col items-center justify-center rounded-lg">
                                <p class="text-3xl font-bold">{{ props.businessCount }}</p>
                                <div>Empresas</div>
                            </div>
                            <div class="w-full h-32 bg-main/50 flex flex-col items-center justify-center rounded-lg">
                                <p class="text-3xl font-bold">0</p>
                                <div>Leads</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="overflow-hidden flex flex-col md:flex-row gap-6"
                >
                    <div class="w-full md:w-2/3 shadow-sm md:rounded-lg bg-white p-6">
                        <header class="mb-3">
                            <h2 class="text-xl font-bold">Últimos projetos</h2>
                        </header>
                    </div>
                    <div class="w-full md:w-1/3 shadow-sm md:rounded-lg bg-white p-6">
                        <header class="mb-3">
                            <h2 class="text-xl font-bold">Últimos leads</h2>
                        </header>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
