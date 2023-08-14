<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Modal } from "bootstrap";

const form = useForm();

const props = defineProps({
  suppliers: Object,
});

onMounted(() => {
});
</script>

<template>
    <Head title="Proveedores" />
    <BreezeAuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Proveedores
        </h2>
        <div class="d-flex justify-content-between">
          <div></div>
          <Link class="btn btn-primary" :href="route('suppliers.create')">Registrar nuevo</Link>
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
                <th class="align-middle">Nombre Completo</th>
                <th class="align-middle">Correo electrónico</th>
                <th class="align-middle">Dirección</th>
                <th class="align-middle">Persona de contacto</th>
                <th class="align-middle">Teléfono</th>
                <th class="align-middle"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="supplier in props.suppliers">
                <td class="align-middle">{{ supplier.name }}</td>
                <td class="align-middle">{{ supplier.email }}</td>
                <td class="align-middle">{{ supplier.address }}</td>
                <td class="align-middle">{{ supplier.contact }}</td>
                <td class="align-middle">{{ supplier.phone }}</td>
                <td class="align-middle" style="text-wrap: nowrap !important;">
                  <Link :href="route('suppliers.update', {id: supplier.id})" class="btn btn-warning m-1">
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