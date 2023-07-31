<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    users: Object,
});

</script>

<template>
    <Head title="Usuarios" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuarios
            </h2>
            <div class="d-flex justify-content-between">
              <div></div>
              <a class="btn btn-primary" :href="route('users.create')">Registrar nuevo</a>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-striped">
                <thead>
                  <tr>
                    <th>Nombre Completo</th>
                    <th>Correo electrónico</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in props.users">
                    <td>{{ user.fullname }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                      <Link :href="route('users.update', {id: user.id})" class="btn btn-link">editar</Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
        </template>
    </BreezeAuthenticatedLayout>
</template>