<script setup>
  import { ref } from 'vue';
  import { Link } from '@inertiajs/inertia-vue3';
  import BreezeNavLink from '@/Components/NavLink.vue';
</script>
<template>
  <!-- Navigation Links -->
  <div class="main-menu">
  <BreezeNavLink :href="route('dashboard')" :active="route().current('dashboard')">
    Inicio
  </BreezeNavLink>
  <BreezeNavLink :href="route('users')" :active="route().current('users')">
    Usuarios
  </BreezeNavLink>
  <BreezeNavLink :href="route('clients')" :active="route().current('clients')">
    Clientes
  </BreezeNavLink>
  <BreezeNavLink :href="route('manufacturers')" :active="route().current('manufacturers')">
    Talleres
  </BreezeNavLink>
  <BreezeNavLink :href="route('suppliers')" :active="route().current('suppliers')">
    Proveedores
  </BreezeNavLink>
  <BreezeNavLink :href="route('inventory')" :active="route().current('inventory')">
    Inventario
  </BreezeNavLink>
  <BreezeNavLink :href="route('orders')" :active="route().current('orders')">
    Pedidos
  </BreezeNavLink>
  <BreezeNavLink :href="route('work-progress')" :active="route().current('work-progress')">
    Trabajos en curso
  </BreezeNavLink>
  <BreezeNavLink :href="route('deliveries')" :active="route().current('deliveries')">
    Entregas
  </BreezeNavLink>
  <BreezeNavLink :href="route('reports')" :active="route().current('reports')">
    Reportes
  </BreezeNavLink>
</div>
</template>