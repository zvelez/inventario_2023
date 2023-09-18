<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
  user: Object,
  roles: Array,
});

const form = useForm({
    password: '',
    repeat: '',
});

const titlePage = 'Actualizar contraseña';

const buttonLabel = 'Actualizar';

const submit = () => {
  form.post(route('profile.password', {id: props.user.id}));
};

</script>

<template>
  <Head title="Perfil" />
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

        <form class="forms-sample" @submit.prevent="submit">
          <div class="form-group">
            <BreezeLabel for="password" class="col-form-label" value="Nueva contraseña" />
            <BreezeInput id="password" type="password" class="form-control" v-model="form.password" required autofocus />
          </div>
          <div class="form-group">
            <BreezeLabel for="repeat" class="col-form-label" value="Repetir contraseña" />
            <BreezeInput id="repeat" type="password" class="form-control" v-model="form.repeat" required />
          </div>

          <div class="d-flex justify-content-between m-2">
              <Link :href="route('dashboard')" class="btn btn-link">Cancelar</Link>

              <BreezeButton class="btn btn-primary">{{ buttonLabel }}</BreezeButton>
          </div>
        </form>
    </template>
  </BreezeAuthenticatedLayout>
</template>