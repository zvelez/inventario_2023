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
  const selected = ref(0);
  console.log(props.filters);

  onMounted(() => {
    loadData();
  });

  const loadData = () => {
    reports.value = [];
    axios.post(route('reports.raw_material_stock'), {order: orderSel.value, select: selected.value})
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
        Materia prima en stock
      </h3>
    </template>
    <div class="reports">
      <div class="filters d-flex justify-content-between">
        <div class="row p-4">
          <div class="form-group col-12 col-sm-6">
            <BreezeLabel for="code" class="col-form-label" value="Ordenar por" />
            <select id="code" class="form-control" v-model="orderSel" @change="loadData">
              <option v-for="(item, index) in props.filters.order" :value="index">{{ item }}</option>
            </select>
          </div>
          <div class="form-group col-12 col-sm-6">
            <BreezeLabel for="code" class="col-form-label" value="Filtrar por" />
            <select id="code" class="form-control" v-model="selected" @change="loadData">
              <option v-for="(item, index) in props.filters.select" :value="index">{{ item }}</option>
            </select>
          </div>
        </div>
        <div>
          <a class="btn btn-link" :href="route('reports.raw_material_stock.download', {order: orderSel, select: selected})">Descargar reporte</a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th class="align-middle">Código de Insumo</th>
              <th class="align-middle">Descripción</th>
              <th class="align-middle">Marca</th>
              <th class="align-middle">Precio unitario</th>
              <th class="align-middle">Stock Inicial</th>
              <th class="align-middle">Entrada</th>
              <th class="align-middle">Salida</th>
              <th class="align-middle">Stock Actual</th>
              <th class="align-middle">Proveedor(es)</th>
              <th class="align-middle">Último abastecimiento</th>
              <th class="align-middle"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="supp in reports">
              <td class="align-middle">{{ supp.code }}</td>
              <td class="align-middle">{{ supp.description }}</td>
              <td class="align-middle">{{ supp.brand }}</td>
              <td class="align-middle text-end">Bs. {{ supp.unitprice }}</td>
              <td class="align-middle text-end">{{ supp.stock_start }}</td>
              <td class="align-middle text-end">{{ supp.stock_in }}</td>
              <td class="align-middle text-end">{{ supp.stock_out }}</td>
              <td :class="['align-middle', 'text-end', 'stock-alert', calcLevel(supp)]">{{ supp.stock_curr }}</td>
              <td class="align-middle">{{ supp.suppliers }}</td>
              <td class="align-middle">{{ supp.order_date }}</td>
              <td class="align-middle" style="text-wrap: nowrap !important;">
                <!--Link :href="route('suppliers.update', {id: supp.id})" class="btn btn-warning m-1">
                  <font-awesome-icon :icon="['fa', 'pen']" />
                </Link-->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>