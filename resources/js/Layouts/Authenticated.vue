<script setup>
import { ref } from 'vue';
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import BreezeNavLink from '@/Components/NavLink.vue';
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/inertia-vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="d-flex justify-content-between h-16">
                      <!-- Logo -->
                      <div class="brand-logo shrink-0 d-flex align-items-center">
                          <Link :href="route('dashboard')">
                              <BreezeApplicationLogo class="block h-9 w-auto" />
                          </Link>
                      </div>
                      <div class="d-flex">
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
                              <BreezeNavLink :href="route('suppliers')" :active="route().current('suppliers')">
                                  Proveedores
                              </BreezeNavLink>
                              <BreezeNavLink :href="route('orders')" :active="route().current('orders')">
                                  Pedidos
                              </BreezeNavLink>
                          </div>
                      </div>

                      <div class="d-flex align-items-center">
                          <!-- Settings Dropdown -->
                          <div class="ml-3 position-relative">
                              <BreezeDropdown align="right" width="48">
                                  <template #trigger>
                                      <span class="d-inline-flex rounded-md">
                                          <button type="button" class="d-inline-flex align-items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                              {{ $page.props.auth.user.name }}
                                              <font-awesome-icon :icon="['fa', 'chevron-down']" />
                                          </button>
                                      </span>
                                  </template>

                                  <template #content>
                                      <BreezeDropdownLink :href="route('logout')" method="post" as="button">
                                          Cerrar sesión
                                      </BreezeDropdownLink>
                                  </template>
                              </BreezeDropdown>
                          </div>
                      </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
