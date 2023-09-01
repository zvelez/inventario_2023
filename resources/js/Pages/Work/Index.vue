<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Modal } from "bootstrap";

import moment from 'moment';

const props = defineProps({
  works: Object,
  statuslist: Array,
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
    <Head title="Trabajos en curso" />
    <BreezeAuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Trabajos en curso
        </h2>
        <div class="d-flex justify-content-between">
          <div class="form-group">
            <select class='form-control' v-model='statusSel' @click="statusSelected">
              <option value='Todos'>- Todos -</option>
              <option v-for="st in props.statuslist" :value='st'>{{ st }}</option>
            </select>
          </div>
          <Link class="btn btn-primary" :href="route('works.create')">Registrar nuevo</Link>
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
                <th class="align-middle">Cliente</th>
                <th class="align-middle">Teléfono</th>
                <th class="align-middle">Fecha de registro</th>
                <th class="align-middle">Fecha de entrega</th>
                <th class="align-middle text-center">Estado</th>
                <th class="align-middle"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="work in worksList">
                <td class="align-middle">{{ work.client.fullname }}</td>
                <td class="align-middle">{{ work.client.phone }}</td>
                <td class="align-middle">{{ moment(work.created_at).format('DD/MM/YYYY') }}</td>
                <td class="align-middle">{{ moment(work.deadline).format('DD/MM/YYYY') }}</td>
                <td class="align-middle text-center">
                  <span :class="{'work-status': true, 'start': work.status=='Iniciado', 'production': work.status=='Producción', 
                                  'test': work.status=='Revisión', 'deliverable': work.status=='Entregable', 'delivered': work.status=='Entregado'}">
                    {{ work.status }}
                  </span>
                </td>
                <td class="align-middle" style="text-wrap: nowrap !important;">
                  <Link :href="route('works.view', {id: work.id})" class="btn btn-info m-1">
                    <font-awesome-icon :icon="['fa', 'eye']" />
                  </Link>
                  <Link :href="route('works.update', {id: work.id})" class="btn btn-warning m-1">
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