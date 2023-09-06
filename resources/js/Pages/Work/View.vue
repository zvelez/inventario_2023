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
      <div class="d-flex justify-content-end">
        <a class="btn btn-warning" :href="route('works.starting_order', {wid: work.id})" v-if="work.status === 'Entregable'">
          Generar orden de despacho de mercadería
        </a>
        <button class="btn btn-success" style="margin-left: 15px;" @click="printDiv">Imprimir</button>
      </div>
      <div id="preview-page" class="w-100 preview-page pb-5">
        <h3 class="titlefont-semibold text-uppercase client-name pt-4 pb-3">
          {{ work.client.fullname }} <span class="date">#{{ work.id }}</span>
        </h3>
        <div class="row border-bottom">
          <dl class="col-12 col-sm-6 col-md-3">
            <dt class="f-label">Correo electrónico:</dt>
            <dd class="f-data">{{ work.client.email }}</dd>
          </dl>
          <dl class="col-12 col-sm-6 col-md-3">
            <dt class="f-label">Teléfono:</dt>
            <dd class="f-data">{{ work.client.phone }}</dd>
          </dl>
        </div>
        <div class="row border-bottom align-items-center">
          <dl class="col-6 col-sm-3">
            <dt class="f-label">Fecha registrada:</dt>
            <dd class="f-data">{{ moment(work.created_at).format('DD/MM/YYYY') }}</dd>
          </dl>
          <dl class="col-6 col-sm-3">
            <dt class="f-label">Fecha de entrega:</dt>
            <dd class="f-data">{{ moment(work.deadline).format('DD/MM/YYYY') }}</dd>
          </dl>
          <span :class="{'col-6': true, 'col-sm-3': true, 'work-status': true, 'start': work.status=='Iniciado', 
                          'production': work.status=='Producción', 'test': work.status=='Revisión', 
                          'deliverable': work.status=='Entregable', 'delivered': work.status=='Entregado'}">
            {{ work.status }}
          </span>
        </div>
        <div class="mt-4">
          <h5>Productos solicitados</h5>

          <div class="card my-2" v-for="prod in work.products">
            <div class="card-header fw-bold">
              {{ prod.name }} <span>#{{ prod.code }}</span>
            </div>
            <div class="card-body">
              <div class="d-flex w-100">
                <div class="col-2 f-label">Cantidad</div>
                <div class="col-2 f-label">Precio unitario</div>
                <div class="col-3 f-label">Taller asignado</div>
                <div class="col-5"></div>
              </div>
              <div class="d-flex w-100">
                <div class="col-2 fw-bold">{{ prod.amount }}</div>
                <div class="col-2 fw-bold">{{ prod.unitprice }}</div>
                <div class="col-3 fw-bold">{{ prod.manufacturer.fullname }}</div>
                <div class="col-5"></div>
              </div>

              <h3 class="pt-2"><small class="text-muted" v-if="prod.supplies.length > 0">Insumos asignados</small></h3>
              <h4 class="text-center lead pt-4" v-if="prod.supplies.length === 0">No han sido asignados insumos</h4>
              <div class="table-responsive" v-if="prod.supplies.length > 0">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th class="align-middle">Código</th>
                      <th class="align-middle">Descripción</th>
                      <th class="align-middle">Marca</th>
                      <th class="align-middle">Cantidad asignada</th>
                      <th class="align-middle"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in prod.supplies">
                      <td class="align-middle">{{ item.code }}</td>
                      <td class="align-middle">{{ item.description }}</td>
                      <td class="align-middle">{{ item.brand }}</td>
                      <td class="align-middle text-end">{{ item.pivot.amount }} {{ item.unit }}</td>
                      <td>
                        <Link class="btn btn-warning m-1 d-print-none" :href="route('works.update', {id: work.id})">
                          <font-awesome-icon :icon="['fa', 'pen']" />
                        </Link>
                      </td>
                    </tr>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <h3 class="pt-2"><small class="text-muted">Historial de entregas</small></h3>
          <Link class="btn btn-link d-print-none" :href="route('works.deliveries.create', {wid: work.id})">
            <font-awesome-icon :icon="['fa', 'plus']" /> Registrar nuevo
          </Link>
        </div>
        <div class="table-responsive" v-if="work.deliveries.length > 0">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th class="align-middle">Producto/Insumo</th>
                <th class="align-middle text-end">Código</th>
                <th class="align-middle">Fecha estimada de entrega</th>
                <th class="align-middle">Fecha de entrega</th>
                <th class="align-middle text-end">Cantidad</th>
                <th class="align-middle">Responsable</th>
                <th class="align-middle">Observaciones</th>
                <th class="align-middle"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in work.deliveries">
                <td class="align-middle">{{ item.in_str }}</td>
                <td class="align-middle text-end">{{ item.code }}</td>
                <td class="align-middle">{{ item.estimatedate }}</td>
                <td class="align-middle">{{ item.deliverydate }}</td>
                <td class="align-middle text-end">{{ item.amount }}</td>
                <td class="align-middle">{{ item.responsible }}</td>
                <td class="align-middle">{{ item.contact }} - {{ item.dnicontact }}</td>
                <td class="align-middle">{{ item.observations }}</td>
                <td class="align-middle">

                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </BreezeAuthenticatedLayout>

</template>