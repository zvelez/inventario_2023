<script setup>
  import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
  import { Head } from '@inertiajs/inertia-vue3';
  import PartialForm from './PartialForm.vue';

  const props = defineProps({
    manufacturer: Object,
  });
</script>

<template>
  <Head title="Taller" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <PartialForm :manufacturer="manufacturer"></PartialForm>
    </template>
  </BreezeAuthenticatedLayout>
</template>