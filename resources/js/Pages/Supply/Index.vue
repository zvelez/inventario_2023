<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Modal } from "bootstrap";

const form = useForm();

const props = defineProps({
  supplies: Object,
});

onMounted(() => {
});
</script>

<template>
  <Head title="Inventario" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <div class="d-flex justify-content-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Inventario
        </h2>
        <div>
          <a class="btn btn-link" :href="route('inventory.entries')">Entradas</a>
          <a class="btn btn-link" :href="route('inventory.deliveries')">Salidas</a>
        </div>
      </div>
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
              <th class="align-middle">Código de Insumo</th>
              <th class="align-middle">Descripción</th>
              <th class="align-middle">Marca</th>
              <th class="align-middle">Precio unitario</th>
              <th class="align-middle">Stock Inicial</th>
              <th class="align-middle">Entrada</th>
              <th class="align-middle">Salida</th>
              <th class="align-middle">Stock Actual</th>
              <th class="align-middle">Proveedor</th>
              <th class="align-middle"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="supp in props.supplies">
              <td class="align-middle">{{ supp.code }}</td>
              <td class="align-middle">{{ supp.description }}</td>
              <td class="align-middle">{{ supp.brand }}</td>
              <td class="align-middle text-end">Bs. {{ supp.unitprice }}</td>
              <td class="align-middle text-end">{{ supp.stock_start }}</td>
              <td class="align-middle text-end">{{ supp.stock_in }}</td>
              <td class="align-middle text-end">{{ supp.stock_out }}</td>
              <td class="align-middle text-end">{{ supp.stock_curr }}</td>
              <td class="align-middle">{{ supp.suppliers }}</td>
              <td class="align-middle" style="text-wrap: nowrap !important;">
                <!--Link :href="route('suppliers.update', {id: supp.id})" class="btn btn-warning m-1">
                  <font-awesome-icon :icon="['fa', 'pen']" />
                </Link-->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>
  </BreezeAuthenticatedLayout>
</template>