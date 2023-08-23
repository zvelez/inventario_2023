<script setup>
  import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
  import { Head } from '@inertiajs/inertia-vue3';

const props = defineProps({
  manufacturer: Object,
});
</script>

<template>
  <Head title="Clientes" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <PartialForm :manufacturer="manufacturer"></PartialForm>
    </template>
  </BreezeAuthenticatedLayout>
</template>