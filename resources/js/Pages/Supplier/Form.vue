<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
  supplier: Object,
});

const form = useForm({
  name: props.supplier.id !== undefined ? props.supplier.name : '',
  email: props.supplier.id !== undefined ? props.supplier.email : '',
  address: props.supplier.id !== undefined ? props.supplier.address : '',
  contact: props.supplier.id !== undefined ? props.supplier.contact : '',
  phone: props.supplier.id !== undefined ? props.supplier.phone : '',
});

const titlePage = props.supplier.id !== undefined ? 
                    'Actualizar los datos del proveedor ' + props.supplier.name : 
                    'Registrar nuevo proveedor';

const buttonLabel = props.supplier.id !== undefined ? 'Actualizar' : 'Registrar';

const submit = () => {
  if(props.supplier.id !== undefined) {
    form.put(route('suppliers.update', {id: props.supplier.id}));
  }
  else {
    form.post(route('suppliers.create'));
  }
};

const clickAction = () => {
  console.log(props.supplier);
};
</script>

<template>
  <Head title="Proveedores" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ titlePage }}
      </h2>

      <BreezeValidationErrors class="mb-4 pb-1" />

      <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>
      <!--button @click="clickAction">Probando</button-->
        <form class="forms-sample" @submit.prevent="submit">
          <div class="form-group">
            <BreezeLabel for="name" class="col-form-label" value="Nombre" />
            <BreezeInput id="name" class="form-control" v-model="form.name" required autofocus />
          </div>
          <div class="form-group">
            <BreezeLabel for="email" class="col-form-label" value="Correo electrónico" />
            <BreezeInput id="email" type="email" class="form-control" v-model="form.email" required autocomplete="email" />
          </div>
          <div class="form-group">
            <BreezeLabel for="address" class="col-form-label" value="Dirección" />
            <BreezeInput id="address" class="form-control" v-model="form.address" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="contact" class="col-form-label" value="Persona de contacto" />
            <BreezeInput id="contact" class="form-control" v-model="form.contact" required />
          </div>
          <div class="form-group">
            <BreezeLabel for="phone" class="col-form-label" value="Teléfono de contacto" />
            <BreezeInput id="phone" type="number" class="form-control" v-model="form.phone" required />
          </div>

          <div class="d-flex justify-content-between m-2">
            <Link :href="route('users')" class="btn btn-link">Cancelar</Link>
            <BreezeButton class="btn btn-primary">{{ buttonLabel }}</BreezeButton>
          </div>
        </form>
    </template>
  </BreezeAuthenticatedLayout>
</template>