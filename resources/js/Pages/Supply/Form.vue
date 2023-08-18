<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue';

const props = defineProps({
  supply: Object,
  order: Object,
});

const form = useForm({
  code: props.supply.id !== undefined ? props.supply.code : '',
  description: props.supply.id !== undefined ? props.supply.description : '',
  brand: props.supply.id !== undefined ? props.supply.brand : '',
  unit: props.supply.id !== undefined ? props.supply.unit : '',
  amount: props.supply.id !== undefined ? props.supply.amount : 0.000,
  unitprice: props.supply.id !== undefined ? props.supply.unitprice : 0.000,
  orderentry_id: props.supply.id !== undefined ? props.supply.orderentry_id : props.order.id,
  op: null,
});

const titlePage = props.supply.id !== undefined ? 
                    'Actualizar los datos del insumo ' + props.supply.code : 
                    'Registrar nuevo insumo en el pedido al proveedor <' + props.order.supplier.name + ' en fecha ' + props.order.date + '>';

const buttonLabel = props.supply.id !== undefined ? 'Actualizar' : 'Añadir más insumos';

const submit = () => {
  console.log(form.data());
  if(props.supply.id !== undefined) {
    form.put(route('suppliers.update', {id: props.supply.id}));
  }
  else {
    form.post(route('orders.add', {oid: props.order.id}));
  }
  form.reset();
};


const clickAction = () => {
  console.log(props.supply);
};
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
      <!--button @click="clickAction">Probando</button-->
        <form class="forms-sample" @submit.prevent="submit">
          <div class="form-group">
            <BreezeLabel for="code" class="col-form-label" value="Código" />
            <BreezeInput id="code" class="form-control" v-model="form.code" required autofocus />
          </div>
          <div class="form-group">
            <BreezeLabel for="description" class="col-form-label" value="Descripción" />
            <textarea id="description" class="form-control" rows="3" v-model="form.description" required></textarea>
          </div>
          <div class="form-group">
            <BreezeLabel for="brand" class="col-form-label" value="Marca" />
            <BreezeInput id="brand" class="form-control" v-model="form.brand" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="amount" class="col-form-label" value="Cantidad" />
            <div class="d-flex justify-content-between">
              <BreezeInput id="unit" class="form-control" v-model="form.unit" style="width: 80px;" required placeholder="Unidad" />
              <BreezeInput id="amount" type="number" class="form-control" v-model="form.amount" step=".001" style="width: calc(100% - 95px);" required />
            </div>
          </div>

          <div class="d-flex justify-content-between m-2">
            <Link :href="route('orders')" class="btn btn-link">Cancelar</Link>
            <div class="d-flex">
              <BreezeButton class="btn btn-success" @click="form.op='end'" v-if="props.supply.id === undefined">Finalizar</BreezeButton>
              <BreezeButton class="btn btn-primary" @click="form.op='next'">{{ buttonLabel }}</BreezeButton>
            </div>
          </div>
        </form>
    </template>
  </BreezeAuthenticatedLayout>
</template>