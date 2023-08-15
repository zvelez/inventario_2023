<script setup>
import { computed } from 'vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    status: String,
});

const form = useForm();

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Confirmación de correo electrónico" />

        <div class="mb-4 text-sm text-gray-600">
            !Gracias por registrarte! Antes de comenzar, ¿podría verificar su dirección de correo electrónico haciendo clic en el enlace que le acabamos de enviar? Si no recibiste el correo electrónico, con gusto te enviaremos otro.
        </div>

        <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent" >
            Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionó durante el registro.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 d-flex align-items-center justify-content-between">
                <BreezeButton :class="{'btn': true, 'btn-success': true, 'opacity-25': form.processing }" :disabled="form.processing">
                    Reenviar correo de validación
                </BreezeButton>

                <Link :href="route('logout')" method="post" as="button" class="text-underline btn btn-danger">Cerrar sesión</Link>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
