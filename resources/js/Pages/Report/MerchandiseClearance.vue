<script setup>
  import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
  import { onMounted, ref } from "vue";
  import { Head, Link } from '@inertiajs/inertia-vue3';
  import BreezeLabel from '@/Components/Label.vue';
  import axios from 'axios';

  const props = defineProps({
    filters: Object,
  });

  const reports = ref([]);
  const orderSel = ref(0);
  console.log(props.filters);

  onMounted(() => {
    loadData();
  });
  
  const loadData = () => {
    reports.value = [];
    axios.post(route('reports.merchandise_clearance'), {order: orderSel.value})
          .then((res) => {
            reports.value = res.data.reports;
          });
  }

  const calcLevel = (supply) => {
    var res = '';
    if(supply.stock_perc < 0.07) {
      res = 'alert-danger';
    }
    else if(supply.stock_perc < 0.15) {
      res = 'alert-warning';
    }
    else if(supply.stock_perc < 0.24) {
      res = 'alert-success';
    }
    else if(supply.stock_perc < 0.37) {
      res = 'alert-god';
    }
    else {
      res = 'alert-full';
    }
    return res;
  }
</script>
<template>
  <Head title="Trabajos en curso" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h3 class="titlefont-semibold text-uppercase client-name pt-4 pb-3">
        Detalle de despacho de mercaderías
      </h3>
    </template>
    <div class="reports">
      <div class="filters d-flex justify-content-between">
        <div class="row p-4">
          <div class="form-group col-12">
            <BreezeLabel for="code" class="col-form-label" value="Ordenar por" />
            <select id="code" class="form-control" v-model="orderSel" @change="loadData">
              <option v-for="(item, index) in props.filters.order" :value="index">{{ item }}</option>
            </select>
          </div>
        </div>
        <div>
          <a class="btn btn-link" :href="route('reports.merchandise_clearance.download', {order: orderSel})">Descargar reporte</a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th class="align-middle">Cod. Trabajo</th>
              <th class="align-middle text-center">F. despacho</th>
              <th class="align-middle">Cliente</th>
              <th class="align-middle">Supervisor</th>
              <th class="align-middle">Recibido por</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in reports">
              <td class="align-middle">{{ order.work_code }}</td>
              <td class="align-middle text-center">{{ order.dispatchdate }}</td>
              <td class="align-middle">{{ order.client }}</td>
              <td class="align-middle">{{ order.supervisor }}</td>
              <td class="align-middle">{{ order.receivedby }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>