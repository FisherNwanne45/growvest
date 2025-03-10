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

const props = defineProps({
  email: {
    type: String,
    required: true
  },
  token: {
    type: String,
    required: true
  },
  'auth_page': Object
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: ''
})
onMounted(() => window.scrollTo(0, 300))
const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation')
  })
}
</script>

<template>

  <Head title="Reset Password" />
  <Breadcrumbs PageName="Reset Password" />
  <div class="container my-20">
    <div class="grid grid-cols-12 gap-[30px]">
      <div class="col-span-12 lg:col-span-6 xl:col-span-5">
        <div class="mini-title">{{ trans('Reset Password') }}</div>
        <h4 class="column-title">
          {{ trans('Reset') }}
          <span class="shape-bg"> {{ trans('Password') }}</span>
        </h4>
        <div>
          {{ trans('Reset your Password.') }}
        </div>
        <AuthLeftSection :data="auth_page" />
      </div>
      <div class="col-span-12 lg:col-span-6 xl:col-span-7">
        <form @submit.prevent="submit"
          class="shadow-box7 mt-10 space-y-4 rounded-md border border-gray-100 bg-white p-8">
          <div class="mb-6 text-center">
            <h4>{{ trans('Reset Password') }}</h4>
          </div>

          <div>
            <label>{{ trans('Email') }} *</label>
            <input readonly type="email" required v-model="form.email" class="from-control"
              placeholder="Enter your email here" />
            <InputFieldError :message="form.errors.email" />
          </div>
          <div>
            <label>{{ trans('Password') }} *</label>
            <input type="password" required v-model="form.password" class="from-control" placeholder="Password"
              autocomplete="new-password" />
            <InputFieldError :message="form.errors.password" />
          </div>
          <div>
            <label>{{ trans('Confirm Password') }} *</label>
            <input type="password" required v-model="form.password_confirmation" class="from-control"
              placeholder="Confirm Password" autocomplete="new-password" />
            <InputFieldError :message="form.errors.password_confirmation" />
          </div>
          <button type="submit" :disabled="form.processing" class="btn btn-primary mt-8 w-full">
            {{ trans('Reset Password') }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
