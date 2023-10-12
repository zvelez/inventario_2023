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
    photo: Object,
  });

  const form = useForm({
    description: props.photo.id !== undefined ? props.photo.description : '',
    file: props.photo.id !== undefined ? props.photo.file : ''
  });

  const titlePage = 'Agregar nueva Fotografía al producto' + props.product.code;

  const buttonLabel = 'Registrar';

  const preview = ref(null);
  const file = ref(null);
  const fileName = ref(null);

  const handleFileChange = (event) => {
    file.value = event.files[0];
    fileName.value = file.value.name;

    let reader = new FileReader();
    reader.readAsDataURL(file.value);

    reader.onload = (e) => {
      preview.value = e.target.result;
      form.file = preview.value;
    };
  }

  const submit = () => {
    //console.log(form.manufacturer_id, manufacturerSel.value, form.supplies);
    if(props.photo.id !== undefined) {
      //form.put(route('products.photo.update', {id: props.product.id}));
    }
    else {
      console.log(form);
      form.post(route('products.photo.add', {id: props.product.id}));
    }
    form.reset();
  };
</script>

<template>
  <Head title="Añadir fotografía" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ titlePage }}
      </h2>
      
      <BreezeValidationErrors class="mb-4 pb-1" />
      <div v-if="$page.props.flash.message"
            class="p-4 mb-4 mt-2 alert alert-success"
            role="alert">
        <span class="font-medium">
            {{ $page.props.flash.message }}
        </span>
      </div>

      <BreezeValidationErrors class="mb-4 pb-1" />

      <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>
      <form class="forms-sample pb-4" @submit.prevent="submit">
        <div
          class="dropzone"
          @dragover.prevent
          @dragenter.prevent
          @dragstart.prevent
          @drop.prevent="handleFileChange($event.dataTransfer)"
        >
          <input
            id="file-input"
            type="file"
            accept="image/png, image/jpeg"
            @change="handleFileChange($event.target)"
            required
          />
          <h4 for="file-input">Clic para buscar o arrastre el archivo</h4>
          <img class="img-fluid" :src="preview" v-if="preview !== null" />
        </div>

        <div class="form-group">
          <BreezeLabel for="description" class="col-form-label" value="Descripción" />
          <BreezeInput id="description" class="form-control" v-model="form.description"/>
        </div>

        <div class="d-flex justify-content-between m-2">
          <Link :href="route('work-progress')" class="btn btn-link">Cancelar</Link>
          <div class="d-flex">
            <BreezeButton class="btn btn-primary">{{ buttonLabel }}</BreezeButton>
          </div>
        </div>
      </form>
    </template>
  </BreezeAuthenticatedLayout>
</template>