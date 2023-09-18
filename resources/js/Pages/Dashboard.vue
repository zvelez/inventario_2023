<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head } from '@inertiajs/inertia-vue3';

console.log(status);
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Dashboard
          </h2>
        </template>

        <div class="py-12">
          <BreezeValidationErrors class="mb-4 pb-1" />
          <div v-if="$page.props.flash.message"
                class="p-4 mb-4 mt-2 alert alert-success"
                role="alert">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
          </div>

          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white border-b border-gray-200">
                Usted está dentro del sistema
              </div>
            </div>
          </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
