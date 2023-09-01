<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

import moment from 'moment';

const form = useForm();

const props = defineProps({
  supplies: Object,
});

console.log(props.supplies);

onMounted(() => {
});

const findManufacturer = (products, id) => {
  const prod = products.find((item) => item.id === id);
  return prod !== null ? prod.manufacturer : null;
}

const findWork = (products, id) => {
  const prod = products.find((item) => item.id === id);
  return prod !== null ? prod.work : null;
}
</script>

<template>
  <Head title="Entradas a Inventario" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <div class="d-flex justify-content-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Salida de Inventario
        </h2>
        <div>
          <a class="btn btn-link" :href="route('inventory')">General</a>
          <a class="btn btn-link" :href="route('inventory.entries')">Entradas</a>
        </div>
      </div>
      <div class="d-flex justify-content-between">
        <div></div>
        <Link class="btn btn-primary" :href="route('works.create')">Registrar Trabajo</Link>
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
              <th class="align-middle">Fecha</th>
              <th class="align-middle">Cantidad</th>
              <th class="align-middle">Precio unitario Bs.</th>
              <th class="align-middle">Importe Bs.</th>
              <th class="align-middle">Taller</th>
              <th class="align-middle">Cód. Trabajo</th>
              <th class="align-middle">Destino</th>
              <th class="align-middle"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="supp in props.supplies">
              <td class="align-middle">{{ supp.code }}</td>
              <td class="align-middle">{{ supp.description }}</td>
              <td class="align-middle">{{ supp.brand }}</td>
              <td class="align-middle">{{ moment(supp.registered_at).format('DD/MM/YYYY') }}</td>
              <td class="align-middle text-end">{{ supp.amount }} {{ supp.unit }}</td>
              <td class="align-middle text-end">{{ supp.unitprice }}</td>
              <td class="align-middle text-end">{{ (supp.unitprice * supp.amount).toFixed(2) }}</td>
              <td class="align-middle text-end">{{ findManufacturer(supp.products, supp.product_id).fullname }}</td>
              <td class="align-middle text-end">
                <Link :href="route('works.view', {id: supp.work_id})" class="btn btn-link">#{{ supp.work_id }}</Link>
              </td>
              <td class="align-middle text-end">{{ findWork(supp.products, supp.product_id).client.fullname }}</td>
              <td class="align-middle" style="text-wrap: nowrap !important;">
                <Link :href="route('products.update', {id: supp.product_id})" class="btn btn-warning m-1">
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