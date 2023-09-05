<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Modal } from "bootstrap";

const form = useForm();
let modalEle = ref(null);
let thisModalObj = null;
let userSel = ref(null);

const props = defineProps({
  users: Object,
});

onMounted(() => {
  thisModalObj = new Modal(modalEle.value);
});

const openModal = (usr) => {
  userSel.value = usr;
  thisModalObj.show();
}

const cancelModal = () => {
  userSel.value = null;
  thisModalObj.hide();
}

const okModal = () => {
  if(userSel.value !== null) {
    form.delete(route('users.delete', {id: userSel.value.id}), {
      onFinish: () => thisModalObj.hide(),
    });
  }
}
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
              <Link class="btn btn-primary" :href="route('users.create')">Registrar nuevo</Link>
            </div>
            <div v-if="$page.props.flash.message"
                  class="p-4 mb-4 mt-2 alert alert-success"
                  role="alert">
                <span class="font-medium">
                    {{ $page.props.flash.message }}
                </span>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-striped">
                <thead>
                  <tr>
                    <th class="align-middle">Nombre Completo</th>
                    <th class="align-middle">Correo electrónico</th>
                    <th class="align-middle">Rol de usuario</th>
                    <th class="align-middle"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in props.users">
                    <td class="align-middle">{{ user.fullname }}</td>
                    <td class="align-middle">{{ user.email }}</td>
                    <td class="align-middle">{{ user.role.name }}</td>
                    <td class="align-middle" style="text-wrap: nowrap !important;">
                      <Link :href="route('users.update', {id: user.id})" class="btn btn-warning m-1">
                        <font-awesome-icon :icon="['fa', 'pen']" />
                      </Link>
                      <button @click="openModal(user)" 
                              :class="{'btn': true, 'btn-success': user.status===1, 'btn-danger': user.status===0, 'm-1': true}">
                        <font-awesome-icon v-if="user.status===1" :icon="['fas', 'user-check']" />
                        <font-awesome-icon v-else :icon="['fas', 'user-lock']" />
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
        </template>
        <div class="modal fade" tabindex="-1" role="dialog" ref="modalEle">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header bg-danger">
                <h5 class="modal-title text-center w-100 text-white fw-bold">
                  Cambio de estado de usuario
                </h5>
              </div>
              <div class="modal-body">
                <p class="text-center" v-if="userSel != null">
                  ¿Realmente desea {{ userSel.status===1? 'bloquear' : 'dDesbloquear' }} al usuario <strong>{{ userSel.fullname }}</strong>?
                </p>
                <p class="text-center" v-else>Algo salio mal.</p>
              </div>
              <div class="modal-footer" v-if="userSel != null">
                <button type="button" class="btn btn-primary" @click="okModal" v-if="userSel.status===1">Bloquear</button>
                <button type="button" class="btn btn-primary" @click="okModal" v-else>Desloquear</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="cancelModal">Cancelar</button>
              </div>
            </div>
          </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>