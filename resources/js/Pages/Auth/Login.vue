<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Formulario de Ingreso" />

        <BreezeValidationErrors class="mb-4 pb-1" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form class="forms-sample" @submit.prevent="submit">
          <div class="form-group mt-2">
                <BreezeLabel for="email" value="Correo electrónico" />
                <BreezeInput id="email" type="email" class="form-control" v-model="form.email" required autofocus autocomplete="username" />
            </div>

            <div class="form-group mt-2">
                <BreezeLabel for="password" value="Contraseña" />
                <BreezeInput id="password" type="password" class="form-control" v-model="form.password" required autocomplete="current-password" />
            </div>

            <div class="form-group mt-4">
                <label class="d-flex align-items-center">
                    <BreezeCheckbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">Recuerdame</span>
                </label>
            </div>

            <div class="d-flex align-items-center justify-content-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-700">
                    ¿Olvidaste tu contraseña?
                </Link>

                <BreezeButton class="ml-4 btn btn-success" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Ingresar
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
