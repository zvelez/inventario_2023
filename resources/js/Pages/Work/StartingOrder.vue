<script setup>
  import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
  import { Head, Link } from '@inertiajs/inertia-vue3';
  import { onMounted, ref } from 'vue';

  import moment from 'moment';

  const props = defineProps({
    work: Object,
    userfullname: String,
  });

  let currDate = ref(moment().format('DD/MM/YYYY kk:mm'));
  let total = ref(0);
  const receivedby = ref('Click para editar');
  let receivedEditor = ref(false);

  setInterval(() => currDate.value = moment().format('DD/MM/YYYY kk:mm'), 1000);

  onMounted(() => {
    props.work.products.forEach(prod => {
      const r = prod.amount * prod.unitprice;
      total.value = total.value + r;
    });
    total.value = total.value.toFixed(2);
    console.log(total.value);
  });

  const printDiv = () => {
    var printContents = document.getElementById('printable-content').innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    location.reload();
  }

  const closeWork = () => {
    axios.post(route('works.starting_order', {wid: props.work.id}), {date: currDate.value, receivedby: receivedby.value})
      .then((res) => {
        printDiv();
      }).catch((error) => {
      });
  }
</script>
<template>
  <Head title="Trabajos en curso" />
  <BreezeAuthenticatedLayout>
    <div class="container-fluid">
      <div class="card w-100">
        <div class="card-body">
          <div id="printable-content" class="w-100">
            <div class="d-flex justify-content-between pb-5">
              <div style="max-width: 20vw;">
                <img class="img-fluid" src="/images/logo.png">
              </div>
              <div class="col-6 d-flex flex-column align-items-center">
                <h4 class="titlefont-semibold text-uppercase text-end client-name pt-4 pb-3">
                  Orden de despacho de mercadería
                </h4>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr class="table-secondary">
                        <th class="align-middle text-center">Cód. Trabajo</th><th class="align-middle text-center">Fecha de despacho</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="align-middle text-center">#{{ work.id }}</td>
                        <td class="align-middle text-center">{{ currDate }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row border-bottom">
              <dl class="col-12 col-sm-6 col-md-3">
                <dt class="f-label">Cliente:</dt>
                <dd class="f-data">{{ work.client.fullname }}</dd>
              </dl>
              <dl class="col-12 col-sm-6 col-md-3">
                <dt class="f-label">Correo electrónico:</dt>
                <dd class="f-data">{{ work.client.email }}</dd>
              </dl>
              <dl class="col-12 col-sm-6 col-md-3">
                <dt class="f-label">Teléfono:</dt>
                <dd class="f-data">{{ work.client.phone }}</dd>
              </dl>
            </div>
            <div class="w-100 py-2">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr class="table-secondary">
                      <th class="align-middle text-center">#</th>
                      <th class="align-middle text-center">Cod. Producto</th>
                      <th class="align-middle text-center">Producto</th>
                      <th class="align-middle text-center">Precio Uni.</th>
                      <th class="align-middle text-center">Cantidad</th>
                      <th class="align-middle text-center">Importe</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(prod, index) in work.products">
                      <td class="align-middle text-end">{{ index }}</td>
                      <td class="align-middle text-end">{{ prod.code }}</td>
                      <td class="align-middle">{{ prod.name }}</td>
                      <td class="align-middle text-end">$us {{ prod.unitprice }}</td>
                      <td class="align-middle text-end">{{ prod.amount }}</td>
                      <td class="align-middle text-end">$us {{ (prod.amount * prod.unitprice ).toFixed(2) }}</td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr class="table-borderless" style="border-width: 0 !important;">
                      <td class="align-middle" style="border-width: 0 !important;" colspan="4"></td>
                      <td class="align-middle fw-bold text-end" style="border-width: 1px !important;">Total</td>
                      <td class="align-middle fw-bold text-end" style="border-width: 1px !important;">$us {{ total }}</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <div class="d-flex justify-content-around">
              <div class="col-3 d-flex flex-column align-items-center">
                <span class="w-100 border-bottom text-center py-2">{{ props.userfullname }}</span>
                <p class="text-center"><strong>Responsable de entrega</strong></p>
              </div>
              <div class="col-3 d-flex flex-column align-items-center">
                <div class="form-group w-100" v-if="receivedEditor">
                  <input class="form-control text-center" @keyup.enter="receivedEditor = false"
                          style="border: 0px; border-bottom: 1px solid #dee2e6 !important;" v-model="receivedby">
                </div>
                <span class="w-100 border-bottom text-center py-2" v-if="!receivedEditor" @click="receivedEditor = true">{{ receivedby }}</span>
                <p class="text-center"><strong>Recibido por</strong></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-end py-4">
        <button class="btn btn-primary" style="margin-left: 15px;" @click="closeWork">Cerrar trabajo</button>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>