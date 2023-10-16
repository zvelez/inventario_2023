<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Modal } from "bootstrap";

import moment from 'moment';

const form = useForm();

const props = defineProps({
  order: Object,
});

const print = () => {
  var printContents = document.getElementById('printable').innerHTML;
  var originalContents = document.body.innerHTML;

  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
};
</script>

<template>
    <Head title="Insumos de un Pedido" />
    <BreezeAuthenticatedLayout>
      <template #header>
        <div class="w-100">
          <div class="d-flex flex-column flex-sm-row justify-content-between" v-if="props.order !== null">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lista de insumos de un Pedido al proveedor</h2>
            <div class="dl-wrapper">
              <dl>
                <dt>Fecha</dt><dd>{{ moment(props.order.date).format('DD/MM/YYYY') }}</dd>
                <dt>Nro de Orden</dt><dd>{{ props.order.id }}</dd>
              </dl>
            </div>
          </div>
          <div class="d-flex justify-content-between">
            <div class="filters">
            </div>
            <Link class="btn btn-primary" :href="route('orders.add', {id: props.order.id})">Agregar insumo</Link>
          </div>
          <div v-if="$page.props.flash.message"
                class="p-4 mb-4 mt-2 alert alert-success"
                role="alert">
              <span class="font-medium">
                  {{ $page.props.flash.message }}
              </span>
          </div>
          <div class="table-responsive" style="margin: 32px auto 42px;" v-if="props.order !== null">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Código</th>
                  <th scope="col">Descripción</th>
                  <th scope="col">Marca</th>
                  <th scope="col">Unidad</th>
                  <th scope="col">Cantidad</th>
                  <th scope="col">Precio Unidad</th>
                  <th scope="col">Precio Total</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in props.order.supplies">
                  <td>{{ item.code }}</td>
                  <td>{{ item.description }}</td>
                  <td>{{ item.brand }}</td>
                  <td>{{ item.unit }}</td>
                  <td>{{ item.amount }}</td>
                  <td class="text-right"><span>{{ item.unitprice }}</span></td>
                  <td class="text-right"><span>{{ item.totalprice }}</span></td>
                  <td class="text-right">
                    <Link :href="route('supplies.update', {id: item.id})" class="btn btn-warning m-1">
                      <font-awesome-icon :icon="['fa', 'pen']" />
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </template>
    </BreezeAuthenticatedLayout>
</template>