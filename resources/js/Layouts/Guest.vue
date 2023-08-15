<script setup>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <div class="d-flex flex-column vh-100 justify-content-center align-items-center bg-gray-100">
        <div>
            <Link href="/">
                <BreezeApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div class="col-12 col-lg-6 p-4 bg-white">
            <slot />
        </div>
    </div>
</template>
