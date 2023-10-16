<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue';

import {Treeselect, ASYNC_SEARCH} from 'vue3-treeselect';
import 'vue3-treeselect/dist/vue3-treeselect.css';

import axios from 'axios';

import moment from 'moment';

const props = defineProps({
  work: Object,
  statuslist: Array,
});

const form = useForm({
  deadline: props.work.id !== undefined ? props.work.deadline : moment().format('YYYY-MM-DD'),
  status: props.work.id !== undefined ? props.work.status : 'Iniciado',
  client_id: props.work.id !== undefined ? props.work.client_id : null,
  created_at: props.work.id !== undefined ? props.work.created_at : moment().format('YYYY-MM-DD'),
});

const titlePage = props.work.id !== undefined ? 
                    'Actualizar los datos del trabajo del cliente ' + props.work.client.fullname + ' en fecha ' + moment(props.work.created_at).format('YYYY-MM-DD') : 
                    'Registrar nuevo Trabajo';

const buttonLabel = props.work.id !== undefined ? 'Actualizar' : 'Registrar un Trabajo';

const submit = () => {
  if(props.work.id !== undefined) {
    form.put(route('works.update', {id: props.work.id}));
  }
  else {
    form.post(route('works.create', {oid: props.work.id}));
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

      <BreezeValidationErrors class="mb-4 pb-1" />

      <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>
        <form class="forms-sample" @submit.prevent="submit">
          <div class="form-group">
            <BreezeLabel for="client_id" class="col-form-label" value="Cliente" />
            <Treeselect id="client_id" class="form-control" :async="true" :load-options="searchClient" v-model="form.client_id" v-if="props.work.id === undefined" required />
            <BreezeInput id="client_id" class="form-control" v-model="props.work.client.fullname" v-if="props.work.id !== undefined" disabled="disabled" />
          </div>
          <div class="form-group">
            <BreezeLabel for="status" class="col-form-label" value="Estado" />
            <select id="status" class='form-control' v-model='form.status' required>
              <option v-for="st in props.statuslist" :value='st'>{{ st }}</option>
            </select>
          </div>
          <div class="form-group">
            <BreezeLabel for="deadline" class="col-form-label" value="Fecha de entrega" />
            <BreezeInput id="deadline" class="form-control" type="date" v-model="form.deadline" required />
          </div>

          <div class="d-flex justify-content-between m-2">
            <Link :href="route('work-progress')" class="btn btn-link">Cancelar</Link>
            <div class="d-flex">
              <BreezeButton class="btn btn-success" @click="form.op='end'" v-if="props.work.id === undefined">Finalizar</BreezeButton>
              <BreezeButton class="btn btn-primary" @click="form.op='next'">{{ buttonLabel }}</BreezeButton>
            </div>
          </div>
        </form>
    </template>
  </BreezeAuthenticatedLayout>
</template>