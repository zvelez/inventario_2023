<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';

import axios from 'axios';

import { ref } from 'vue';

const props = defineProps({
  supply: Object,
});

let isLoad = ref(true);

let message = ref(null);

const input = {
  amount: props.supply.amount,
  unitprice: props.supply.unitprice,
}

const update = async () => {
  isLoad.value = false;
  message.value = null;
  const url = route('supplies.receive', {id: props.supply.id});
  try {
    const request = await axios.put(url, input);
    isLoad.value = true;
    //console.log(request);
    if(request.status === 200) {
      input.amount = request.data.amount;
      input.unitprice = request.data.unitprice;
      message.value = 'Actualizado';
    }
    setTimeout(() => message.value = null, 4000);
  }
  catch (err) {
    //console.log(err.message);
    message.value = 'Se produjo un error';
    isLoad.value = true;
    setTimeout(() => message.value = null, 4000);
  }
}

</script>


<template>
  <div class="row p-1 my-2 border">
    <div class="col-12 col-sm-12 col-md-4">
      <span>{{ props.supply.code }}</span> <strong>{{ props.supply.description }}</strong> <span>{{ props.supply.brand }}</span>
    </div>
    <div class="col-12 col-sm-6 col-md-3 d-flex align-items-center">
      <BreezeInput id="deliverydate" type="number" class="form-control" v-model="input.amount"/>
      <span>{{ props.supply.unit }}</span>
    </div>
    <div class="col-12 col-sm-6 col-md-3 d-flex align-items-center">
      <span>Bs </span>
      <BreezeInput id="deliverydate" type="number" class="form-control" v-model="input.unitprice"/>
    </div>
    <div class="col-12 col-sm-6 col-md-2 d-flex align-items-center justify-content-center position-relative">
      <BreezeButton class="btn btn-info w-100" @click="update" v-if="isLoad">Actualizar</BreezeButton>
      <div class="loading-animate" v-else><font-awesome-icon :icon="['fa', 'spinner']" /></div>
      <span v-if="message !== null" class="position-absolute end-0 bottom-100 message-fast">{{ message }}</span>
    </div>
  </div>
</template>