<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Modal } from "bootstrap";

const form = useForm();

const props = defineProps({
  clients: Object,
});

onMounted(() => {
});
</script>

<template>
    <Head title="Clientes" />
    <BreezeAuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Clientes
        </h2>
        <div class="d-flex justify-content-between">
          <div></div>
          <Link class="btn btn-primary" :href="route('clients.create')">Registrar nuevo</Link>
        </div>
        <div v-if="$page.props.flash.message"
              class="p-4 mb-4 mt-2 alert alert-success"
              role="alert">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th class="align-middle">Nombre Completo</th>
                <th class="align-middle">Correo electrónico</th>
                <th class="align-middle">Dirección</th>
                <th class="align-middle">Ciudad</th>
                <th class="align-middle">Teléfono</th>
                <th class="align-middle"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="client in props.clients">
                <td class="align-middle">{{ client.fullname }}</td>
                <td class="align-middle">{{ client.email }}</td>
                <td class="align-middle">{{ client.address }}</td>
                <td class="align-middle">{{ client.city + ' - ' + client.state }}</td>
                <td class="align-middle">{{ client.phone }}</td>
                <td class="align-middle" style="text-wrap: nowrap !important;">
                  <Link :href="route('clients.update', {id: client.id})" class="btn btn-warning m-1">
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