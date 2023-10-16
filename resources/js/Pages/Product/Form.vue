<script setup>
  import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
  import BreezeButton from '@/Components/Button.vue';
  import BreezeInput from '@/Components/Input.vue';
  import BreezeLabel from '@/Components/Label.vue';
  import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
  import PartialForm from '../Manufacturer/PartialForm.vue';
  import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
  import { onMounted, ref } from 'vue';

  import SearchInput from '@/Components/SearchInput.vue';

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
    supplies: props.product.id !== undefined ? props.product.supplies : [],
    op: null,
  });

  const titlePage = props.product.id !== undefined ? 
                      'Actualizar los datos del producto ' + props.product.code : 
                      'Registrar nuevo Producto';
  const workName = 'Trabajo para ' + props.work.client.fullname + ' en fecha ' + moment(props.work.created_at).format('DD/MM/YYYY');

  const buttonLabel = props.product.id !== undefined ? 'Actualizar' : 'Registrar un Producto';

  const manufacturerSel = ref(null);

  let suppliesList = ref(form.supplies);
  let supplySel = ref(null);
  //console.log(suppliesList.value);
  let searchComp = ref(null);

  const submit = () => {
    console.log(manufacturerSel.value);
    form.manufacturer_id = props.product.id !== undefined? props.product.manufacturer_id : manufacturerSel.value.id;
    form.supplies = suppliesList.value;
    console.log(form.manufacturer_id, manufacturerSel.value, form.supplies);
    if(props.product.id !== undefined) {
      form.put(route('products.update', {id: props.product.id}));
    }
    else {
      form.post(route('works.add', {wid: props.work.id}));
    }
    location.reload();
  };

  const addItem = () => {
    console.log(props.product);
    if(props.product.id === undefined) {
      addItemCreate();
    }
    else {
      addItemUpdate();
    }
  };

  const addItemCreate = () => {
    const valueD = Object.assign({}, supplySel.value.data);
    console.log('addItemCreate', supplySel.value, valueD);
    suppliesList.value.push({
      id: valueD.id,
      code: valueD.code,
      description: valueD.description,
      brand: valueD.brand,
      unit: valueD.unit,
      amount: valueD.amount,
      pivot: {
        amount: valueD.amount,
      }
    });
    supplySel.value = null;
    searchComp.value.cleanData();
  };

  const addItemUpdate = () => {
    const valueD = Object.assign({}, supplySel.value.data);
    console.log(supplySel.value, valueD);
    suppliesList.value.push({
      id: valueD.id,
      code: valueD.code,
      description: valueD.description,
      brand: valueD.brand,
      unit: valueD.unit,
      amount: valueD.amount,
    });
    supplySel.value = null;
    searchComp.value.cleanData();
  };
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
            <BreezeLabel for="client" class="col-form-label" value="Trabajo" />
            <BreezeInput id="client" class="form-control" :value="workName" v-if="props.work.id !== undefined" disabled="disabled" />
          </div>
          <div class="form-group">
            <BreezeLabel for="manufacturer_id" class="col-form-label" value="Taller" />
            <div class="d-flex justify-content-between" v-if="props.product.id === undefined">
              <SearchInput id="manufacturer_id" v-model="manufacturerSel" :url-api="route('manufacturers.search')" required></SearchInput>
              <BreezeButton class="btn btn-success d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#addManufacturer" type="button" 
                    style="width: 34px; height: 34px; margin-left: 15px;">
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
            <BreezeInput id="amount" type="number" class="form-control" v-model="form.amount" step=".001" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="unitprice" class="col-form-label" value="Precio unitario" />
            <BreezeInput id="unitprice" type="number" class="form-control" v-model="form.unitprice" step=".001" required />
          </div>
          <div class="supplies-wrapper">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <th>Código</th>
                  <th>Descripción</th>
                  <th>Marca</th>
                  <th>Cantidad</th>
                  <th></th>
                </thead>
                <tbody>
                  <tr v-for="item in suppliesList">
                    <td>{{ item.code }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.brand }}</td>
                    <td>
                      <div class="form-group d-flex justify-content-between align-items-end">
                        <BreezeInput type="number" class="form-control" v-model="item.pivot.amount" step=".001" placeholder="cantidad" />
                        <span>{{ item.unit }}</span>
                      </div>
                    </td>
                    <td>
                      <BreezeButton type="button" class="btn btn-primary d-flex justify-content-center align-items-center" @click="addItem">
                        <font-awesome-icon :icon="['fa', 'plus']" />
                      </BreezeButton>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <SearchInput ref="searchComp" v-model="supplySel" :url-api="route('supplies.search')"></SearchInput>
                    </td>
                    <td><span v-if="supplySel !== null">{{ supplySel.data.description }}</span></td>
                    <td><span v-if="supplySel !== null">{{ supplySel.data.brand }}</span></td>
                    <td>
                      <div class="form-group d-flex justify-content-between align-items-end"  v-if="supplySel !== null">
                        <BreezeInput type="number" class="form-control" v-model="supplySel.data.amount" step="1" placeholder="cantidad" />
                        <span style="padding-left: 4px;">{{ supplySel.data.unit }}</span>
                      </div>
                    </td>
                    <td>
                      <BreezeButton type="button" class="btn btn-primary d-flex justify-content-center align-items-center" @click="addItem">
                        <font-awesome-icon :icon="['fa', 'plus']" />
                      </BreezeButton>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="d-flex justify-content-between m-2">
            <Link :href="route('work-progress')" class="btn btn-link">Cancelar</Link>
            <div class="d-flex">
              <BreezeButton class="btn btn-success" @click="form.op='end'" v-if="props.product.id === undefined">Finalizar</BreezeButton>
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