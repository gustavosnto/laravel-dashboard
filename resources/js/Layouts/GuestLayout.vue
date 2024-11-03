<script setup></script>

<template>
    <div
        class="w-full h-screen flex justify-between"
    >
        <div class="w-full md:w-2/3 hidden md:block h-screen bg-[url('/images/bg-login.jpg')] bg-cover bg-no-repeat"></div>
        <div
            class="w-full md:w-1/3 flex flex-col justify-center px-10"
            >
            <slot />
        </div>
    </div>
</template>
