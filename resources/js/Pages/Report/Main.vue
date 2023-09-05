<script setup>
  import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
  import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

  const props = defineProps({
    reports: Object,
  });
</script>
<template>
  <Head title="Trabajos en curso" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h3 class="titlefont-semibold text-uppercase client-name pt-4 pb-3">
        Reportes
      </h3>
    </template>
    <div class="reports container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-6 col-md-4" v-for="item in props.reports">
          <div class="reports-link">
            <div class="w-100 report-title">
              <a class="text-decoration-none" :href="item.url">{{ item.label }}</a>
              <p>{{ item.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>