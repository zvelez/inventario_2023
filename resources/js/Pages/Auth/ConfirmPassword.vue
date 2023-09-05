<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    })
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-gray-600">
            Esta es un área segura de la aplicación. Por favor, confirme su contraseña antes de continuar.
        </div>

        <BreezeValidationErrors class="mb-4 pb-1" />

        <form @submit.prevent="submit">
            <div class="form-group">
                <BreezeLabel for="password" value="Contraseña" />
                <BreezeInput id="password" type="password" class="mt-1 form-control" v-model="form.password" required autocomplete="current-password" autofocus />
            </div>

            <div class="d-flex justify-content-end mt-4">
                <BreezeButton class="ml-4" :class="{'btn': true, 'btn-success': true, 'opacity-25': form.processing }" :disabled="form.processing">
                    Confirmar
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
