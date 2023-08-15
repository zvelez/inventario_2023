<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600">
            ¿Olvidaste tu contraseña? Ningún problema. Simplemente háganos saber su dirección de correo electrónico y le enviaremos un enlace de restablecimiento de contraseña que le permitirá elegir una nueva.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <BreezeValidationErrors class="mb-4" />

        <form @submit.prevent="submit">
            <div class="form-group">
                <BreezeLabel for="email" value="Correo electrónico" />
                <BreezeInput id="email" type="email" class="mt-1 form-control" v-model="form.email" required autofocus autocomplete="username" />
            </div>

            <div class="d-flex align-items-center justify-content-end mt-4">
                <BreezeButton :class="{'btn': true, 'btn-success': true ,'opacity-25': form.processing }" :disabled="form.processing">
                    Enlace de restablecimiento de contraseña de correo electrónico
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
