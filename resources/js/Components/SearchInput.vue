<script setup>
import { ref } from 'vue';


  const props = defineProps({
    modelValue: Object,
    urlApi: String,
  });
  const emit = defineEmits(['update:modelValue'])

  const textSearch = ref('');
  const resultList = ref([]);
  const isLoad = ref(false);
  
  const search = (event) => {
    isLoad.value = false;
    axios.post(props.urlApi, {text: event.target.value})
      .then((res) => {
        //console.log(event.target.value, res.status, res.data)
        isLoad.value = true;
        if(res.status === 200) {
          resultList.value = res.data;
        }
        else {
          resultList.value = [];
        }
      }).catch((error) => {
        isLoad.value = true;
        resultList.value = [];
      });
  }

  const onSelect = (item) => {
    textSearch.value = item.label;
    resultList.value = [];
    isLoad.value = false;
    emit('update:modelValue', item);
  }
</script>
<template>
  <div class="search-wrapper">
    <div class="input-wrapper d-flex justify-content-between">
      <input class="form-control" v-model="textSearch" placeholder="Buscar..." @input="search">
    </div>
    <div class="suffix">
      <font-awesome-icon :icon="['fa', 'magnifying-glass']" />
    </div>
    <div class="result-wrapper" v-if="isLoad">
      <ul>
        <li v-for="item in resultList" @click="onSelect(item)">{{ item.label }}</li>
      </ul>
    </div>
  </div>
</template>
<style lang="scss">
  .search-wrapper {
    width: 100%;
    position: relative;

    .suffix {
      position: absolute;
      top: 2px;
      right: 2px;
      background-color: white;
      width: 34px;
      height: 34px;
      display: flex;
      justify-content: center;
      align-items: center;

      .svg-inline--fa {
        color: #dee2e6;
      }
    }

    .result-wrapper {
      position: absolute;
      top: calc(100% - 0.375rem);
      left: 0;
      width: 100%;
      max-height: 50vh;
      padding-top: 0.375rem;
      background-color: white;
      overflow-y: auto;
      border: 1px solid #dee2e6;
      border-top: 1px solid #f6f6f6;
      border-radius: 0.375rem;
      border-top-left-radius: 0;
      border-top-right-radius: 0;

      ul {
        padding-left: 0;
        margin-bottom: 0.5rem;
        
        li {
          list-style: none;
          color: #71767b;
          padding: 6px 10px;
          cursor: pointer;

          &:hover {
            background-color: #f6f6f6;
          }
        }
      }
    }
  }
</style>