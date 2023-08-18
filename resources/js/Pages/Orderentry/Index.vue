<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Modal } from "bootstrap";

const form = useForm();

const props = defineProps({
  orders: Object,
});

onMounted(() => {
});
</script>

<template>
    <Head title="Proveedores" />
    <BreezeAuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Pedidos a proveedor
        </h2>
        <div class="d-flex justify-content-between">
          <div></div>
          <Link class="btn btn-primary" :href="route('orders.create')">Registrar nuevo</Link>
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
                <th class="align-middle">Proveedor</th>
                <th class="align-middle">Fecha</th>
                <th class="align-middle">Fecha estimada de entrega</th>
                <th class="align-middle">Responsable de Recepción</th>
                <th class="align-middle">Insumos</th>
                <th class="align-middle">Cantidad Total</th>
                <th class="align-middle"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in props.orders">
                <td class="align-middle">{{ order.supplier.name }}</td>
                <td class="align-middle">{{ order.date }}</td>
                <td class="align-middle">{{ order.estimateddeliverydate }}</td>
                <td class="align-middle">{{ order.deliveryreceptionist }}</td>
                <td class="align-middle">
                  <p v-for="supp in order.supplies">{{ supp.resume_str }}</p>
                </td>
                <td class="align-middle">Bs{{ order.amounttotal }}</td>
                <td class="align-middle" style="text-wrap: nowrap !important;">
                  <Link :href="route('orders.view', {id: order.id})" class="btn btn-primary m-1">
                    <font-awesome-icon :icon="['fa', 'eye']" />
                  </Link>
                  <Link :href="route('orders.update', {id: order.id})" class="btn btn-warning m-1">
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