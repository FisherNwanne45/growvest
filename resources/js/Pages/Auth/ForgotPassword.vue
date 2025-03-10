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

defineProps({
  status: {
    type: String
  },
  'auth_page': Object
})
onMounted(() => window.scrollTo(0, 300))
const form = useForm({
  email: ''
})

const submit = () => {
  form.post(route('password.email'))
}
</script>

<template>

  <Head title="Forgot Password" />
  <Breadcrumbs PageName="Forgot Password" />
  <div class="container my-20">
    <div class="grid grid-cols-12 gap-[30px]">
      <div class="col-span-12 lg:col-span-6 xl:col-span-5">
        <div class="mini-title">{{ trans('Forgot Password') }}</div>
        <h4 class="column-title">
          {{ trans('Forgot') }}
          <span class="shape-bg"> {{ trans('Password') }}</span>
        </h4>
        <div>
          {{ trans('Forgot your password? No problem, reset now') }}
        </div>
        <AuthLeftSection :data="auth_page" />
      </div>
      <div class="col-span-12 lg:col-span-6 xl:col-span-7">
        <form @submit.prevent="submit"
          class="shadow-box7 mt-10 space-y-4 rounded-md border border-gray-100 bg-white p-8">
          <div v-if="status" class="mb-4 text-sm text-green-600">
            {{ status }}
          </div>
          <div>
            <label>{{ trans('Email') }} *</label>
            <input type="email" required v-model="form.email" class="from-control"
              placeholder="Enter your email here" />
            <InputFieldError :message="form.errors.email" />
          </div>
          <button type="submit" :disabled="form.processing" class="btn btn-primary mt-8 w-full">
            {{ trans('Email Password Reset Link') }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
