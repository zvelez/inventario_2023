<script setup>
  import { computed, onMounted, ref } from 'vue';
  import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
  import BreezeDropdown from '@/Components/Dropdown.vue';
  import BreezeDropdownLink from '@/Components/DropdownLink.vue';
  import { Link, usePage  } from '@inertiajs/inertia-vue3';
  import MenuLinks from '@/Components/MenuLinks.vue';
  import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

  const showingNavigationDropdown = ref(false);
  
  const page = usePage();
  const user = page.props.value.auth?.user;
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

            <div class="d-none d-sm-flex">
              <MenuLinks></MenuLinks>
            </div>

            <div class="d-none d-sm-flex align-items-center">
              <!-- Settings Dropdown -->
              <div class="ml-3 position-relative">
                <BreezeDropdown align="right" width="48">
                  <template #trigger>
                    <span class="d-inline-flex rounded-md">
                      <button type="button"
                        class="d-inline-flex align-items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <font-awesome-icon :icon="['fa', 'chevron-down']" />
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <p class="mx-4 my-2 text-bold" v-if="user !== null">{{ user.fullname }}</p>
                    <BreezeDropdownLink :href="route('profile')" as="button">
                      Editar perfil
                    </BreezeDropdownLink>
                    <BreezeDropdownLink :href="route('profile.password')" as="button">
                      Cambio de contraseña
                    </BreezeDropdownLink>
                    <BreezeDropdownLink :href="route('logout')" method="post" as="button">
                      Cerrar sesión
                    </BreezeDropdownLink>
                  </template>
                </BreezeDropdown>
              </div>
            </div>

            <div class="d-block d-sm-none">
              <nav class="navbar">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                      data-bs-target="#menuMobile" aria-bs-controls="menuMobile" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
            </nav>
            </div>
          </div>
          <div class="collapse navbar-collapse" id="menuMobile">
            <MenuLinks></MenuLinks>
            <p class="mx-4 my-2 text-bold" v-if="user !== null">{{ user.fullname }}</p>
            <BreezeDropdownLink :href="route('profile')" as="button">
              Editar perfil
            </BreezeDropdownLink>
            <BreezeDropdownLink :href="route('profile.password')" as="button">
              Cambio de contraseña
            </BreezeDropdownLink>
            <BreezeDropdownLink :href="route('logout')" method="post" as="button">
              Cerrar sesión
            </BreezeDropdownLink>
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
