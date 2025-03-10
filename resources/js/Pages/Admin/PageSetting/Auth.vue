<script setup>
import { onBeforeMount } from "vue";

import SpinnerBtn from "@/Components/Admin/SpinnerBtn.vue";
import { useOptionUpdateStore } from "@/Store/Admin/optionUpdate";

const props = defineProps(['data'])
const form = useOptionUpdateStore()

onBeforeMount(() => {
  let properties = ['about_section', 'why_choose', 'achievement', 'top_bar', 'socials']
  properties.forEach(key => (props.data[key] = props.data[key] || {}))

})

</script>

<template>
  <form @submit.prevent="form.submit('auth_page', data)">
    <h6>{{ trans('Auth Page Contents') }}</h6>
    <div class="p-3 mt-2 mb-10 bg-opacity-50 border rounded bg-slate-50 dark:bg-slate-800 dark:border-slate-500">
      <div class="mb-2">
        <label>{{ trans('Email') }}</label>
        <input type="text" class="input" v-model="data.email" />
      </div>
      <div class="mb-2">
        <label>{{ trans('Phone') }}</label>
        <input type="text" class="input" v-model="data.phone" />
      </div>

      <div class="mb-2">
        <label>{{ trans('Office Address') }}</label>
        <input type="text" class="input" v-model="data.address" />
      </div>
    </div>

    <div class="mb-2">
      <SpinnerBtn :processing="form.processing" :btn-text="trans('Save Changes')" />
    </div>
  </form>
</template>
