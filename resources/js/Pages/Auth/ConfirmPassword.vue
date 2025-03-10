<script setup>
import { onMounted } from "vue";

import AuthLeftSection from "@/Components/AuthLeftSection.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import InputFieldError from "@/Components/InputFieldError.vue";
import DefaultLayout from "@/Layouts/Default.vue";
import {
  Head,
  useForm,
} from "@inertiajs/vue3";

defineOptions({ layout: DefaultLayout })
defineProps(['auth_page'])

const form = useForm({
  password: ''
})
onMounted(() => window.scrollTo(0, 300))
const submit = () => {
  form.post(route('password.confirm'), {
    onFinish: () => form.reset()
  })
}
</script>

<template>

  <Head title="Confirm Password" />
  <Breadcrumbs PageName="Confirm Password" />
  <div class="container my-20">
    <div class="grid grid-cols-12 gap-[30px]">
      <div class="col-span-12 lg:col-span-6 xl:col-span-5">
        <div class="mini-title">{{ trans('Confirm Password') }}</div>
        <h4 class="column-title">
          {{ trans('Confirm') }}
          <span class="shape-bg"> {{ trans('Password') }}</span>
        </h4>
        <div>
          {{ trans('Please confirm your password before continuing.') }}
        </div>
        <AuthLeftSection :data="auth_page" />
      </div>
      <div class="col-span-12 lg:col-span-6 xl:col-span-7">
        <form @submit.prevent="submit"
          class="shadow-box7 mt-10 space-y-4 rounded-md border border-gray-100 bg-white p-8">
          <div>
            <label>{{ trans('Password') }} *</label>
            <input type="password" required v-model="form.password" class="from-control" placeholder="Enter Password" />
            <InputFieldError :message="form.errors.password" />
          </div>
          <button type="submit" :disabled="form.processing" class="btn btn-primary mt-8 w-full">
            {{ trans('Confirm') }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
