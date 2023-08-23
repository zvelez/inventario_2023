<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import PartialForm from '../Manufacturer/PartialForm.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue';

import {Treeselect, ASYNC_SEARCH} from 'vue3-treeselect';
import 'vue3-treeselect/dist/vue3-treeselect.css';

import axios from 'axios';

import moment from 'moment';

const props = defineProps({
  product: Object,
  work: Object,
});

const form = useForm({
  code: props.product.id !== undefined ? props.product.code : '',
  name: props.product.id !== undefined ? props.product.name : '',
  amount: props.product.id !== undefined ? props.product.amount : 0,
  unitprice: props.product.id !== undefined ? props.product.unitprice : '0.00',
  manufacturer_id: props.product.id !== undefined ? props.product.manufacturer_id : null,
});

const titlePage = props.product.id !== undefined ? 
                    'Actualizar los datos del producto ' + props.product.code : 
                    'Registrar nuevo Producto';
const workName = 'Trabajo para ' + props.work.client.fullname + ' en fecha ' + moment(props.work.created_at).format('DD/MM/YYYY');

const buttonLabel = props.product.id !== undefined ? 'Actualizar' : 'Registrar un Producto';

const submit = () => {
  if(props.product.id !== undefined) {
    form.put(route('products.update', {id: props.product.id}));
  }
  else {
    form.post(route('products.create', {wid: props.product.id}));
  }
};

const searchClient = ({ action, searchQuery, callback }) => {
      if (action === ASYNC_SEARCH) {
        console.log(searchQuery);
        axios.post(route('clients.search'), {text: searchQuery})
          .then((res) => {
            console.log(res.status, res.data)
            if(res.status === 200) {
              callback(null, res.data);
            }
            else {
              callback(null, []);
            }
          }).catch((error) => {
            callback(null, []);
          });
        
      }
    }
</script>

<template>
  <Head title="Insumos" />
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
            <BreezeLabel for="client" class="col-form-label" value="Trabajo" />
            <BreezeInput id="client" class="form-control" :value="workName" v-if="props.work.id !== undefined" disabled="disabled" />
          </div>
          <div class="form-group">
            <BreezeLabel for="manufacturer_id" class="col-form-label" value="Taller" />
            <div class="d-flex justify-content-between" v-if="props.product.id === undefined">
              <Treeselect id="manufacturer_id" class="form-control" :async="true" :load-options="searchClient" v-model="form.manufacturer_id" required />
              <BreezeButton class="btn btn-success d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#addManufacturer" type="button" 
                    style="width: 52px; height: 52px; margin-left: 15px;">
                <font-awesome-icon :icon="['fa', 'plus']" />
              </BreezeButton>
            </div>
            <BreezeInput id="manufacturer_id" class="form-control" v-model="props.product.manufacturer.fullname" v-if="props.product.id !== undefined" disabled="disabled" />
          </div>
          <div class="form-group">
            <BreezeLabel for="code" class="col-form-label" value="Código" />
            <BreezeInput id="code" class="form-control" v-model="form.code" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="name" class="col-form-label" value="Nombre" />
            <BreezeInput id="name" class="form-control" v-model="form.name" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="amount" class="col-form-label" value="Cantidad" />
            <BreezeInput id="amount" type="number" class="form-control" v-model="form.amount" step="1" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="unitprice" class="col-form-label" value="Precio unitario" />
            <BreezeInput id="unitprice" type="number" class="form-control" v-model="form.unitprice" step=".01" required />
          </div>

          <div class="d-flex justify-content-between m-2">
            <Link :href="route('work-progress')" class="btn btn-link">Cancelar</Link>
            <div class="d-flex">
              <BreezeButton class="btn btn-success" @click="form.op='end'" v-if="props.work.id === undefined">Finalizar</BreezeButton>
              <BreezeButton class="btn btn-primary" @click="form.op='next'">{{ buttonLabel }}</BreezeButton>
            </div>
          </div>
        </form>
        <div class="modal fade" id="addManufacturer" tabindex="-1" aria-labelledby="addManufacturer" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" data-bs-config={backdrop:true}>
            <div class="modal-content">
              <div class="modal-body">
                <PartialForm :manufacturer="{}"></PartialForm>
              </div>
            </div>
          </div>
        </div>
    </template>
  </BreezeAuthenticatedLayout>
</template>