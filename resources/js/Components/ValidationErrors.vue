<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';

const errors = computed(() => usePage().props.value.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);
</script>

<template>
    <div v-if="hasErrors">
        <div class="font-medium badge bg-danger">¡Vaya! Algo salió mal.</div>

        <ul class="mt-3 alert alert-danger">
            <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
        </ul>
    </div>
</template>
