<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps(['href', 'active']);

const classes = computed(() => props.active
    ? 'inline-flex items-center px-1 pt-1 border-bottom border-info border-1'
    : 'inline-flex items-center px-1 pt-1'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
