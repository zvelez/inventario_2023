<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

import moment from 'moment';
import Treeselect from 'vue3-treeselect';
import 'vue3-treeselect/dist/vue3-treeselect.css';

const props = defineProps({
  order: Object,
  suppliers: Array
});

const form = useForm({
  date: props.order.id !== undefined ? props.order.date : moment().format('YYYY-MM-DD'),
  solicitantarea: props.order.id !== undefined ? props.order.solicitantarea : '',
  solicitantmanager: props.order.id !== undefined ? props.order.solicitantmanager : '',
  solicitantdate: props.order.id !== undefined ? props.order.solicitantdate : moment().format('YYYY-MM-DD'),
  deliveryaddress: props.order.id !== undefined ? props.order.deliveryaddress : '',
  deliveryreceptionist: props.order.id !== undefined ? props.order.deliveryreceptionist : '',
  estimateddeliverydate: props.order.id !== undefined ? props.order.estimateddeliverydate : '',
  supplier_id: props.order.id !== undefined ? props.order.supplier_id : null,
});

const titlePage = props.order.id !== undefined ? 
                    'Actualizar los datos del pedido ' + props.order.date : 
                    'Registrar nuevo pedido al proveedor';

const buttonLabel = props.order.id !== undefined ? 'Actualizar' : 'Registrar';

const submit = () => {
  if(props.order.id !== undefined) {
    form.put(route('orders.update', {id: props.order.id}));
  }
  else {
    form.post(route('orders.create'));
  }
};

const clickAction = () => {
  console.log(suppSel);
};
</script>

<template>
  <Head title="Pedido al proveedor" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ titlePage }}
      </h2>

      <BreezeValidationErrors class="mb-4" />

      <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>
      <button @click="clickAction">Probando</button>
        <form class="forms-sample" @submit.prevent="submit">
          <div class="form-group">
            <BreezeLabel for="date" class="col-form-label" value="Fecha" @click="clickAction"/>
            <BreezeInput id="date" type="date" class="form-control" v-model="form.date" required autofocus />
          </div>
          <div class="form-group">
            <BreezeLabel for="supplier_id" class="col-form-label" value="Proveedor" />
            <Treeselect id="supplier_id" class="form-control" :options="suppliers" v-model="form.supplier_id" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="solicitantarea" class="col-form-label" value="Área solicitante" />
            <BreezeInput id="solicitantarea" class="form-control" v-model="form.solicitantarea" required autocomplete="solicitantarea" />
          </div>
          <div class="form-group">
            <BreezeLabel for="solicitantmanager" class="col-form-label" value="Gerente solicitante" />
            <BreezeInput id="solicitantmanager" class="form-control" v-model="form.solicitantmanager" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="solicitantdate" class="col-form-label" value="Fecha de solicitud" />
            <BreezeInput id="solicitantdate" type="date" class="form-control" v-model="form.solicitantdate" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="deliveryaddress" class="col-form-label" value="Dirección de entrega" />
            <BreezeInput id="deliveryaddress" class="form-control" v-model="form.deliveryaddress" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="deliveryreceptionist" class="col-form-label" value="Responsable de recepción" />
            <BreezeInput id="deliveryreceptionist" class="form-control" v-model="form.deliveryreceptionist" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="estimateddeliverydate" class="col-form-label" value="Fecha estimada de entrega" />
            <BreezeInput id="estimateddeliverydate" type="date" class="form-control" v-model="form.estimateddeliverydate" required />
          </div>

          <div class="d-flex justify-content-between m-2">
            <Link :href="route('users')" class="btn btn-link">Cancelar</Link>
            <BreezeButton class="btn btn-primary">{{ buttonLabel }}</BreezeButton>
          </div>
        </form>
    </template>
  </BreezeAuthenticatedLayout>
</template>