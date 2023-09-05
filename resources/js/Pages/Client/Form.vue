<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
  client: Object,
});

const form = useForm({
    fullname: props.client.id !== undefined ? props.client.fullname : '',
    email: props.client.id !== undefined ? props.client.email : '',
    state: props.client.id !== undefined ? props.client.state : 0,
    address: props.client.id !== undefined ? props.client.address : '',
    city: props.client.id !== undefined ? props.client.city : '',
    phone: props.client.id !== undefined ? props.client.phone : '',
});

const titlePage = props.client.id !== undefined ? 
                    'Actualizar los datos del cliente ' + props.client.fullname : 
                    'Registrar nuevo cliente';

const buttonLabel = props.client.id !== undefined ? 'Actualizar' : 'Registrar';

const submit = () => {
  if(props.client.id !== undefined) {
    form.put(route('clients.update', {id: props.client.id}));
  }
  else {
    form.post(route('clients.create'));
  }
};

const clickAction = () => {
  console.log(props.client);
};
</script>

<template>
  <Head title="Clientes" />
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
            <BreezeLabel for="fullname" class="col-form-label" value="Nombre del Cliente" />
            <BreezeInput id="fullname" type="fullname" class="form-control" v-model="form.fullname" required autofocus />
          </div>
          <div class="form-group">
            <BreezeLabel for="email" class="col-form-label" value="Correo electrónico" />
            <BreezeInput id="email" type="email" class="form-control" v-model="form.email" required autocomplete="email" />
          </div>
          <div class="form-group">
            <BreezeLabel for="address" class="col-form-label" value="Dirección" />
            <BreezeInput id="address" class="form-control" v-model="form.address" required />
          </div>
          
          <div class="d-flex flex-wrap w-100 justify-content-between">
            <div class="form-group col-12 col-sm-5">
              <BreezeLabel for="state" class="col-form-label" value="Estado" />
              <BreezeInput id="state" class="form-control" v-model="form.state" required />
            </div>
            <div class="form-group col-12 col-sm-5">
              <BreezeLabel for="city" class="col-form-label" value="Ciudad" />
              <BreezeInput id="city" class="form-control" v-model="form.city" required />
            </div>
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