<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Modal } from 'bootstrap';

const props = defineProps({
  manufacturer: Object,
});

const form = useForm({
    fullname: props.manufacturer.id !== undefined ? props.manufacturer.fullname : '',
    email: props.manufacturer.id !== undefined ? props.manufacturer.email : '',
    contact: props.manufacturer.id !== undefined ? props.manufacturer.contact : '',
    address: props.manufacturer.id !== undefined ? props.manufacturer.address : '',
    description: props.manufacturer.id !== undefined ? props.manufacturer.description : '',
    phone: props.manufacturer.id !== undefined ? props.manufacturer.phone : '',
});

const titlePage = props.manufacturer.id !== undefined ? 
                    'Actualizar los datos del taller ' + props.manufacturer.fullname : 
                    'Registrar nuevo taller';

const buttonLabel = props.manufacturer.id !== undefined ? 'Actualizar' : 'Registrar';

const submit = () => {
  if(props.manufacturer.id !== undefined) {
    form.put(route('manufacturers.update', {id: props.manufacturer.id}));
  }
  else {
    form.post(route('manufacturers.create'));
    closeModal();
  }
};

const cancelForm = () => {
  const modalElement = document.getElementById('addManufacturer');
  if(modalElement !== null) {
    closeModal();
  }
  else {
    window.location.href = route('products');
  }
}

const closeModal = () => {
  const modalElement = document.getElementById('addManufacturer');
  if(modalElement !== null) {
    const modal = Modal.getOrCreateInstance(modalElement);
    modal.hide();
  }
}
</script>

<template>
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ titlePage }}
  </h2>
  <BreezeValidationErrors class="mb-4" />

  <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
    {{ status }}
  </div>
  <form class="forms-sample" @submit.prevent="submit">
    <div class="form-group">
      <BreezeLabel for="fullname" class="col-form-label" value="Nombre del taller" />
      <BreezeInput id="fullname" type="fullname" class="form-control" v-model="form.fullname" required autofocus />
    </div>
    <div class="form-group">
      <BreezeLabel for="email" class="col-form-label" value="Correo electrónico" />
      <BreezeInput id="email" type="email" class="form-control" v-model="form.email" required autocomplete="email" />
    </div>
    <div class="form-group">
      <BreezeLabel for="contact" class="col-form-label" value="Contacto" />
      <BreezeInput id="contact" class="form-control" v-model="form.contact" required />
    </div>
    <div class="form-group">
      <BreezeLabel for="address" class="col-form-label" value="Dirección" />
      <BreezeInput id="address" class="form-control" v-model="form.address" required />
    </div>
    <div class="form-group">
      <BreezeLabel for="phone" class="col-form-label" value="Teléfono" />
      <BreezeInput id="phone" class="form-control" v-model="form.phone" required />
    </div>
    <div class="form-group">
      <BreezeLabel for="description" class="col-form-label" value="Descripción" />
      <textarea id="description" class="form-control" rows="3" v-model="form.description"></textarea>
    </div>

    <div class="d-flex justify-content-between m-2">
      <BreezeButton type="button" class="btn btn-secondary" @click="cancelForm">Cancelar</BreezeButton>
      <BreezeButton class="btn btn-primary">{{ buttonLabel }}</BreezeButton>
    </div>
  </form>
</template>