<script setup>
  import { ref } from 'vue';
  import { Link, usePage } from '@inertiajs/inertia-vue3';
  import BreezeNavLink from '@/Components/NavLink.vue';

  const menu = usePage().props.value.menulinks;
  //console.log(menu);
</script>
<template>
  <!-- Navigation Links -->
  <div class="main-menu">
    <BreezeNavLink :href="route('dashboard')" :active="route().current('dashboard')">
      Inicio
    </BreezeNavLink>
    <BreezeNavLink :href="menulink.url" :active="menulink.active" v-for="menulink in menu">
      {{ menulink.label }}
    </BreezeNavLink>
  </div>
</template>