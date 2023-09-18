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
    fullname: props.user.id !== undefined ? props.user.fullname : '',
    email: props.user.id !== undefined ? props.user.email : '',
    role_id: props.user.id !== undefined ? props.user.role_id : 0,
});

const titlePage = props.user.id !== undefined ? 
                    'Actualizar los datos del usuario ' + props.user.fullname : 
                    'Registrar nuevo usuario';

const buttonLabel = props.user.id !== undefined ? 'Actualizar' : 'Registrar';

const submit = () => {
  if(props.user.id !== undefined) {
    form.put(route('users.update', {id: props.user.id}));
  }
  else {
    form.post(route('users.create'));
  }
};

</script>

<template>
  <Head title="Usuarios" />
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
            <BreezeLabel for="fullname" class="col-form-label" value="Nombre Completo" />
            <BreezeInput id="fullname" type="fullname" class="form-control" v-model="form.fullname" required autofocus />
          </div>
          <div class="form-group">
            <BreezeLabel for="email" class="col-form-label" value="Correo electrónico" />
            <BreezeInput id="email" type="email" class="form-control" v-model="form.email" required autocomplete="email" />
          </div>
          <div class="form-group" v-if="props.roles != null">
            <BreezeLabel for="role_id" class="col-form-label" value="Rol de usuario" />
            <select id="role_id" class="form-control" v-model="form.role_id" required>
              <option value="0">- Seleccione un Rol -</option>
              <option v-for="role in props.roles" :value="role.id">{{ role.name }}</option>
            </select>
          </div>

          <div class="d-flex justify-content-between m-2">
              <Link :href="route('users')" class="btn btn-link">Cancelar</Link>

              <BreezeButton class="btn btn-primary">{{ buttonLabel }}</BreezeButton>
          </div>
        </form>
    </template>
  </BreezeAuthenticatedLayout>
</template>