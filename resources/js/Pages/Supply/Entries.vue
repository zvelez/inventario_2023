<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const form = useForm();

const props = defineProps({
  supplies: Object,
});

onMounted(() => {
});
</script>

<template>
  <Head title="Entradas a Inventario" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <div class="d-flex justify-content-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Entradas a Inventario
        </h2>
        <div>
          <a class="btn btn-link" :href="route('inventory')">General</a>
          <a class="btn btn-link" :href="route('inventory.deliveries')">Salidas</a>
        </div>
      </div>
      <div class="d-flex justify-content-between">
        <div></div>
        <Link class="btn btn-primary" :href="route('orders.create')">Registrar Orden</Link>
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
              <th class="align-middle">Cód. Insumo</th>
              <th class="align-middle">Descripción</th>
              <th class="align-middle">Marca</th>
              <th class="align-middle">Proveedor</th>
              <th class="align-middle">Cantidad</th>
              <th class="align-middle">Precio unitario Bs.</th>
              <th class="align-middle">F. estimada</th>
              <th class="align-middle">Fecha de entrega</th>
              <th class="align-middle">Doc. referencia</th>
              <th class="align-middle"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="supp in props.supplies">
              <td class="align-middle">{{ supp.code }}</td>
              <td class="align-middle">{{ supp.description }}</td>
              <td class="align-middle">{{ supp.brand }}</td>
              <td class="align-middle">{{ supp.order.supplier.name }}</td>
              <td class="align-middle text-end">{{ supp.amount }} {{ supp.unit }}</td>
              <td class="align-middle text-end">{{ supp.unitprice }}</td>
              <td class="align-middle text-end">{{ supp.order.estimateddeliverydate_str }}</td>
              <td class="align-middle text-end">{{ supp.order.deliverydate_str }}</td>
              <td class="align-middle text-end">{{ supp.order.deliveryref }}</td>
              <td class="align-middle" style="text-wrap: nowrap !important;">
                <Link :href="route('supplies.update', {id: supp.id})" class="btn btn-warning m-1">
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