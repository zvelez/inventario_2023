<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Modal } from "bootstrap";

import moment from 'moment';

const form = useForm();

const props = defineProps({
  works: Object,
});

onMounted(() => {
});
</script>

<template>
    <Head title="Trabajos en curso" />
    <BreezeAuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Trabajos en curso
        </h2>
        <div class="d-flex justify-content-between">
          <div></div>
          <Link class="btn btn-primary" :href="route('works.create')">Registrar nuevo</Link>
        </div>
        <div v-if="$page.props.flash.message"
              class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
              role="alert">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th class="align-middle">Cliente</th>
                <th class="align-middle">Teléfono</th>
                <th class="align-middle">Fecha de registro</th>
                <th class="align-middle">Fecha de entrega</th>
                <th class="align-middle">Estado</th>
                <th class="align-middle"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="work in props.works">
                <td class="align-middle">{{ work.client.fullname }}</td>
                <td class="align-middle">{{ work.client.phone }}</td>
                <td class="align-middle">{{ moment(work.created_at).format('DD/MM/YYYY') }}</td>
                <td class="align-middle">{{ moment(work.deadline).format('DD/MM/YYYY') }}</td>
                <td class="align-middle">{{ work.status }}</td>
                <td class="align-middle" style="text-wrap: nowrap !important;">
                  <Link :href="route('works.update', {id: work.id})" class="btn btn-warning m-1">
                    <font-awesome-icon :icon="['fa', 'pen']" />
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </template>
    </BreezeAuthenticatedLayout>
</template>