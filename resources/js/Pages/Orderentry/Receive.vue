<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

import RowEdit from '../Supplier/RowEdit.vue';

import moment from 'moment';
import Treeselect from 'vue3-treeselect';
import 'vue3-treeselect/dist/vue3-treeselect.css';

const props = defineProps({
  order: Object,
  suppliers: Array
});

const form = useForm({
  deliverydate: props.order.deliverydate !== null ? props.order.deliverydate : moment().format('YYYY-MM-DD'),
  deliverynote: props.order.deliverynote,
  deliveryref: props.order.deliveryref,
  deliverydoc: props.order.deliverydoc,
});

const titlePage = 'Registro de recepción del pedido en fecha ' + props.order.date + ' por ' + props.order.supplier.name;

const buttonLabel = 'Registrar';

const submit = () => {
  form.post(route('orders.receive', {id: props.order.id}));
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
        <form class="forms-sample" @submit.prevent="submit">
          <div class="form-group">
            <BreezeLabel for="deliverydate" class="col-form-label" value="Fecha"/>
            <BreezeInput id="deliverydate" type="date" class="form-control" v-model="form.deliverydate" required autofocus />
          </div>
          <div class="form-group">
            <BreezeLabel for="deliveryref" class="col-form-label" value="Referencia" />
            <BreezeInput id="deliveryref" class="form-control" v-model="form.deliveryref" autocomplete="solicitantarea" />
            <input type="file" class="form-control" @input="form.deliverydoc = $event.target.files[0]" />
            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
              {{ form.progress.percentage }}%
            </progress>
          </div>
          <div class="form-group">
            <BreezeLabel for="deliverynote" class="col-form-label" value="Nota" />
            <textarea id="deliverynote" class="form-control" rows="3" v-model="form.deliverynote"></textarea>
          </div>

          <div class="d-flex justify-content-between m-2">
            <Link :href="route('users')" class="btn btn-link">Cancelar</Link>
            <BreezeButton class="btn btn-primary">{{ buttonLabel }}</BreezeButton>
          </div>
        </form>
        <div class="w-100">
          <h4>Actualizar precios y cantidades de los insumos</h4>
          <RowEdit v-for="item in props.order.supplies" :supply="item"></RowEdit>
        </div>
    </template>
  </BreezeAuthenticatedLayout>
</template>