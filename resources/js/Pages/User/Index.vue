<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';

const props = defineProps({
    users: Object,
});

const clickAction = () => {
  console.log(props.users);
};
</script>

<template>
    <Head title="Usuarios" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuarios
            </h2>
            <table class="">
              <thead>
                <td>Nombre Completo</td>
                <td>Correo electrónico</td>
              </thead>
              <tbody>
                <tr v-for="user in props.users">
                  <td>{{ user.fullname }}</td>
                  <td>{{ user.email }}</td>
                </tr>
              </tbody>
            </table>
        </template>
    </BreezeAuthenticatedLayout>
</template>