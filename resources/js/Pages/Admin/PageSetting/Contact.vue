<script setup>
import { onBeforeMount } from "vue";

import SpinnerBtn from "@/Components/Admin/SpinnerBtn.vue";
import { useOptionUpdateStore } from "@/Store/Admin/optionUpdate";

const props = defineProps(['data'])
const form = useOptionUpdateStore()

</script>

<template>
  <form @submit.prevent="form.submit('contact_page', data)">
    <h6>{{ trans('Contact Page Contents') }}</h6>
    <div class="p-3 mt-2 mb-10 bg-opacity-50 border rounded bg-slate-50 dark:bg-slate-800 dark:border-slate-500">
     
      <div class="mb-2">
        <label>{{ trans('Page Title') }}</label>
        <input type="text" class="input" v-model="data.page_title" />
      </div>

      <div class="mb-2">
        <label>{{ trans('Section Top Title') }}</label>
        <input type="text" class="input" v-model="data.top_title" />
      </div>

      <div class="mb-2">
        <label>{{ trans('Section Title') }}</label>
        <input type="text" class="input" v-model="data.title" />
      </div>

      <div class="mb-2">
        <label>{{ trans('Section Description') }}</label>
        <input type="text" class="input" v-model="data.description" />
      </div>

      <div class="mb-2">
        <label>{{ trans('Section Email') }}</label>
        <input type="text" class="input" v-model="data.email" />
      </div>
      <div class="mb-2">
        <label>{{ trans('Section Phone') }}</label>
        <input type="text" class="input" v-model="data.phone" />
      </div>
      
      <div class="mb-2">
        <label>{{ trans('Section Office Address') }}</label>
        <input type="text" class="input" v-model="data.address" />
      </div>
    </div>

    <div class="mb-2">
      <SpinnerBtn :processing="form.processing" :btn-text="trans('Save Changes')" />
    </div>
  </form>
</template>
