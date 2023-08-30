<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Modal } from "bootstrap";

import moment from 'moment';

const props = defineProps({
  works: Object,
  status: Array,
});

let worksList = ref(props.works);
let statusSel = ref('Todos');
const isLoad = ref(true);

const statusSelected = async() => {
  isLoad.value = false;
  const url = route('works.filters');
  try {
    const request = await axios.post(url, {status: statusSel.value});
    isLoad.value = true;
    if(request.status === 200) {
      worksList.value = request.data;
    }
  }
  catch (err) {
    //console.log(err.message);
    message.value = 'Se produjo un error';
    isLoad.value = true;
    setTimeout(() => message.value = null, 4000);
  }
}

onMounted(() => {
});
</script>

<template>
    <Head title="Entregas" />
    <BreezeAuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Entregas
        </h2>
        <div class="d-flex justify-content-between">
          <div></div>
          <Link class="btn btn-primary" :href="route('works.create')">Registrar nuevo</Link>
        </div>
        <div v-if="$page.props.flash.message"
              class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
              role="alert">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>
        <div class="row">
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 py-4" v-for="work in worksList">
            <div class="card">
              <div class="card-header d-flex justify-content-between">
                <h5>{{ work.client.fullname }} <a :href="route('works.view', {id: work.id})">#{{ work.id }}</a></h5>
                <span :class="{'work-status': true, 'start': work.status=='Iniciado', 'production': work.status=='Producción', 
                                'test': work.status=='Revisión', 'deliverable': work.status=='Entregable', 'delivered': work.status=='Entregado'}">
                  {{ work.status }}
                </span>
              </div>
              <div class="card-body">
                <dl class="d-flex">
                  <dt class="pe-1">Fecha registrada: </dt><dd>{{ moment(work.created_at).format('DD/MM/YYYY') }}</dd>
                </dl>
                <dl class="d-flex">
                  <dt class="pe-1">Fecha de entrega:</dt><dd>{{ moment(work.deadline).format('DD/MM/YYYY') }}</dd>
                </dl>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <th><small>Código</small></th><th class=" text-nowrap"><small>F. estimada</small></th><th><small>Cantidad</small></th>
                    </thead>
                    <tbody>
                      <tr v-for="item in work.deliveries">
                        <td class="align-middle text-nowrap">
                          <font-awesome-icon class="text-success" :icon="['fas', 'truck-arrow-right']" v-if="item.in === 0" />
                          <font-awesome-icon class="text-primary" :icon="['fas', 'truck-ramp-box']" v-if="item.in === 1" />
                          <small> {{ item.code }}</small>
                        </td>
                        <td class="align-middle"><small>{{ item.estimatedate }}</small></td>
                        <td class="align-middle"><small>{{ item.amount }}</small></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="d-flex justify-content-center">
                  <a class="btn btn-link" :href="route('works.deliveries.create', {wid: work.id})">
                    <font-awesome-icon :icon="['fa', 'plus']" /> <small>Registro de actividad</small>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </BreezeAuthenticatedLayout>
</template>