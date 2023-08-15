<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Reestablecer Contraseña" />

        <BreezeValidationErrors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <BreezeLabel for="email" value="Email" />
                <BreezeInput id="email" type="email" class="mt-1 form-control" v-model="form.email" required autofocus autocomplete="username" />
            </div>

            <div class="form-group mt-4">
                <BreezeLabel for="password" value="Password" />
                <BreezeInput id="password" type="password" class="mt-1 form-control" v-model="form.password" required autocomplete="new-password" />
            </div>

            <div class="form-group mt-4">
                <BreezeLabel for="password_confirmation" value="Confirm Password" />
                <BreezeInput id="password_confirmation" type="password" class="mt-1 form-control" v-model="form.password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="d-flex align-items-center justify-content-end mt-4">
                <BreezeButton :class="{'btn': true, 'btn-success': true,'opacity-25': form.processing }" :disabled="form.processing">
                    Reestablecer
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
