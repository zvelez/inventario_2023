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
  delivery: Object,
  work: Object,
});

const form = useForm({
  in: props.delivery.id !== undefined ? props.delivery.in : 0,
  code: props.delivery.id !== undefined ? props.delivery.code : '',
  amount: props.delivery.id !== undefined ? props.delivery.amount : '',
  deliverydate: props.delivery.id !== undefined ? props.delivery.deliverydate : null,
  estimatedate: props.delivery.id !== undefined ? props.delivery.estimatedate : null,
  responsible: props.delivery.id !== undefined ? props.delivery.responsible : '',
  contact: props.delivery.id !== undefined ? props.delivery.contact : null,
  dnicontact: props.delivery.id !== undefined ? props.delivery.dnicontact : null,
  observations: props.delivery.id !== undefined ? props.delivery.observations : null,
});

const titlePage = props.delivery.id !== undefined ? 
                    'Actualizar los datos del pedido ' + props.delivery.date : 
                    'Registrar nuevo pedido para el Trabajo #' + props.work.id;

const buttonLabel = props.delivery.id !== undefined ? 'Actualizar' : 'Registrar';

const submit = () => {
  if(props.delivery.id !== undefined) {
    form.put(route('works.deliveries.update', {wid: props.work.id, id: props.delivery.id}));
  }
  else {
    form.post(route('works.deliveries.create', {wid: props.work.id}));
  }
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
            <BreezeLabel for="code" class="col-form-label" value="Código del producto/insumo" />
            <BreezeInput id="code" class="form-control" v-model="form.code" required autocomplete="code" />
          </div>
          <div class="form-group">
            <BreezeLabel for="responsible" class="col-form-label" value="Responsable" />
            <BreezeInput id="responsible" class="form-control" v-model="form.responsible" required autocomplete="responsible" />
          </div>
          <div class="form-group">
            <BreezeLabel for="contact" class="col-form-label" value="Contacto del Taller" />
            <BreezeInput id="contact" class="form-control" v-model="form.contact" autocomplete="contact" />
          </div>
          <div class="form-group">
            <BreezeLabel for="dnicontact" class="col-form-label" value="CI del contacto" />
            <BreezeInput id="dnicontact" class="form-control" v-model="form.dnicontact" autocomplete="dnicontact" />
          </div>
          <div class="form-group">
            <BreezeLabel for="estimatedate" class="col-form-label" value="Fecha estimada de entrega"/>
            <BreezeInput id="estimatedate" type="date" class="form-control" v-model="form.estimatedate" />
          </div>
          <div class="form-group">
            <BreezeLabel for="deliverydate" class="col-form-label" value="Fecha de entrega"/>
            <BreezeInput id="deliverydate" type="date" class="form-control" v-model="form.deliverydate" autofocus />
          </div>
          <div class="form-group">
            <BreezeLabel for="amount" class="col-form-label" value="Cantidad" />
            <BreezeInput id="amount" type="number" step="1" class="form-control" v-model="form.amount" />
          </div>
          <div class="form-group">
            <BreezeLabel for="observations" class="col-form-label" value="Observaciones" />
            <textarea id="observations" class="form-control" rows="3" v-model="form.observations"></textarea>
          </div>

          <div class="d-flex justify-content-between m-2">
            <Link :href="route('users')" class="btn btn-link">Cancelar</Link>
            <BreezeButton class="btn btn-primary">{{ buttonLabel }}</BreezeButton>
          </div>
        </form>
    </template>
  </BreezeAuthenticatedLayout>
</template>