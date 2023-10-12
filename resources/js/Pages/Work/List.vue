<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

import moment from 'moment';

const props = defineProps({
  work: Object,
});

const printDiv = () => {
     var printContents = document.getElementById('preview-page').innerHTML;
     document.body.innerHTML = printContents;
     window.print();
    location.reload();
}

</script>
<template>
  <Head title="Trabajos en curso" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <div id="preview-page" class="w-100 preview-page pb-5">
        <div class="d-flex justify-content-end">
          <button class="btn btn-success" style="margin-left: 15px;" @click="printDiv">Imprimir</button>
        </div>
        <h3 class="titlefont-semibold text-uppercase client-name pt-4 pb-3">
          {{ work.client.fullname }} <span class="date">#{{ work.id }}</span>
        </h3>

        <div class="d-flex justify-content-between">
          <h3 class="pt-2"><small class="text-muted">Historial de entregas</small></h3>
          <Link class="btn btn-link d-print-none" :href="route('works.deliveries.create', {wid: work.id})">
            <font-awesome-icon :icon="['fa', 'plus']" /> Registrar nuevo
          </Link>
        </div>
        
        <div class="row">
          <div class="col-12 col-sm-6" v-for="prod in work.products">
            <div class="card">
              <div class="card-body position-relative">
                <h4>{{ prod.name }}</h4>
                <div class="dropdown position-absolute top-0 end-0">
                  <button class="btn btn-white dropdown-toggle position-absolute top-0 end-0" 
                          type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                  <ul class="dropdown-menu">
                    <li><Link class="btn btn-link" :href="route('products.update', {id: prod.id})">Editar</Link></li>
                    <li><Link class="btn btn-link" :href="route('products.photo.add', {id: prod.id})">Agregar fotografía</Link></li>
                  </ul>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-6"><strong>Código:</strong> {{ prod.code }}</div>
                  <div class="col-12 col-sm-6"><strong>Cantidad:</strong> {{ prod.amount }}</div>
                  <div class="col-12 col-sm-6"><strong>Precio unitario:</strong> {{ prod.unitprice }}</div>
                </div>
                <div class="gallery-wrapper w-100" v-if="prod.gallery.length > 0">
                  <div class="w-100" v-for="photo in prod.gallery">
                    <img class="img-fluid" :src="photo.photourl"/>
                    <small>{{ photo.description }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </BreezeAuthenticatedLayout>

</template>