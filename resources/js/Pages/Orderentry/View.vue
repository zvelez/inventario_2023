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
    <Head title="Proveedores" />
    <BreezeAuthenticatedLayout>
      <template #header>
        <div class="d-flex justify-content-between">
          <div></div>
          <button class="btn btn-success" @click="print"><font-awesome-icon :icon="['fa', 'print']" />Imprimir Orden de Compra</button>
        </div>
        
        <div id="printable" class="w-100">
          <div class="d-flex flex-column flex-sm-row justify-content-between" v-if="props.order !== null">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalle de Pedido</h2>
            <div class="dl-wrapper">
              <dl>
                <dt>Fecha</dt><dd>{{ moment(props.order.date).format('DD/MM/YYYY') }}</dd>
                <dt>Nro de Orden</dt><dd>{{ props.order.id }}</dd>
              </dl>
            </div>
          </div>
          <div class="d-flex flex-column flex-sm-row justify-content-between" style="margin-top: 32px;" v-if="props.order !== null">
            <div class="dl-wrapper col-12 col-sm-6">
              <h4>Datos del proveedor</h4>
              <dl>
                <dt v-if="props.order.supplier.name !== null">Nombre</dt>
                <dd v-if="props.order.supplier.name !== null">{{ props.order.supplier.name }}</dd>
                
                <dt v-if="props.order.supplier.contact !== null">Persona de contacto</dt>
                <dd v-if="props.order.supplier.contact !== null">{{ props.order.supplier.contact }}</dd>
                
                <dt v-if="props.order.supplier.address !== null">Dirección</dt>
                <dd v-if="props.order.supplier.address !== null">{{ props.order.supplier.address }}</dd>
              </dl>
              <h4 style="margin-top: 32px;">Datos administrativos</h4>
              <dl>
                <dt v-if="props.order.solicitantarea !== null">Área solicitante</dt>
                <dd v-if="props.order.solicitantarea !== null">{{ props.order.solicitantarea }}</dd>
                
                <dt v-if="props.order.solicitantmanager !== null">Encargado de la solicitud</dt>
                <dd v-if="props.order.solicitantmanager !== null">{{ props.order.solicitantmanager }}</dd>
                
                <dt v-if="props.order.solicitantdate !== null">Fecha de la solicitud</dt>
                <dd v-if="props.order.solicitantdate !== null">{{ props.order.solicitantdate }}</dd>
              </dl>
            </div>
            <div class="dl-wrapper col-12 col-sm-6">
              <h4>Datos de entrega</h4>
              <dl>
                <dt v-if="props.order.deliverydate !== null">Fecha de Entrega</dt>
                <dd v-if="props.order.deliverydate !== null">{{ moment(props.order.deliverydate).format('DD/MM/YYYY') }}</dd>
                
                <dt v-if="props.order.estimateddeliverydate !== null">Fecha estimada</dt>
                <dd v-if="props.order.estimateddeliverydate !== null">{{ moment(props.order.estimateddeliverydate).format('DD/MM/YYYY') }}</dd>
                
                <dt v-if="props.order.deliveryaddress !== null">Dirección</dt>
                <dd v-if="props.order.deliveryaddress !== null">{{ props.order.deliveryaddress }}</dd>
                
                <dt v-if="props.order.deliveryreceptionist !== null">Encargado de recepción</dt>
                <dd v-if="props.order.deliveryreceptionist !== null">{{ props.order.deliveryreceptionist }}</dd>
                
                <dt v-if="props.order.deliverydoc !== null">Documento de referencia</dt>
                <dd v-if="props.order.deliverydoc !== null"><a target="_blank" :href="props.order.deliverydoc">{{ props.order.deliveryref }}</a></dd>
                
                <dt v-if="props.order.deliverynote !== null">Notas</dt>
                <dd v-if="props.order.deliverynote !== null">{{ props.order.deliverynote }}</dd>
              </dl>
            </div>
          </div>
          <div class="table-responsive" style="margin: 32px auto 42px;" v-if="props.order !== null">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th scope="col">Código</th>
                  <th scope="col">Descripción</th>
                  <th scope="col">Marca</th>
                  <th scope="col">Unidad</th>
                  <th scope="col">Cantidad</th>
                  <th scope="col">Precio Unidad</th>
                  <th scope="col">Precio Total</th>
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
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td style="border-width: 0;"></td>
                  <td style="border-width: 0;"></td>
                  <td style="border-width: 0;"></td>
                  <td style="border-width: 0;"></td>
                  <td style="border-width: 0;"></td>
                  <td class="text-bold text-uppercase">Total</td>
                  <td class="table-total"><span>Bs </span><span>{{ props.order.amounttotal }}</span></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="w-100 d-flex justify-content-between" style="padding: 10vh 0 30px;">
            <div></div>
            <div style="width: 200px; border-bottom: 1px solid black"></div>
          </div>
        </div>
      </template>
    </BreezeAuthenticatedLayout>
</template>