<script setup>
import {
  computed,
  onMounted,
} from "vue";

import AuthLeftSection from "@/Components/AuthLeftSection.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import DefaultLayout from "@/Layouts/Default.vue";
import {
  Head,
  useForm,
} from "@inertiajs/vue3";

defineOptions({ layout: DefaultLayout })

const props = defineProps({
  status: {
    type: String
  },
  'auth_page': Object
})

const form = useForm({})
onMounted(() => window.scrollTo(0, 300))
const submit = () => {
  form.post(route('verification.send'))
}
const verificationLinkSent = computed(() => props.status === 'verification-link-sent')
</script>

<template>

  <Head title="Email Verification" />
  <Breadcrumbs PageName="Email Verification" class="mb-5" />
  <div class="container mb-10">
    <div class="grid grid-cols-12 gap-[30px]">
      <div class="col-span-12 lg:col-span-6 xl:col-span-5">
        <div class="mini-title">{{ trans('Email verification') }}</div>
        <h4 class="column-title">
          {{ trans('Email') }}
          <span class="shape-bg"> {{ trans('verification') }}</span>
        </h4>

        <AuthLeftSection :data="auth_page" />
      </div>
      <div class="col-span-12 lg:col-span-6 xl:col-span-7">
        <form @submit.prevent="submit"
          class="shadow-box7 mt-10 space-y-4 rounded-md border border-gray-100 bg-white p-8">
          <div class="mb-4 text-center text-green-600" v-if="verificationLinkSent">
            {{
              trans(`A new verification link has been sent to the email address you provided during
            registration.`)
            }}
          </div>
          <div class="mb-4 text-center text-green-700" v-else>
            {{
              trans(`Thanks for signing up! Before getting started, could you verify your email address by clicking
            on the link we just emailed to you? If you didn't receive the email, we will gladly send you
            another.`)
            }}
          </div>
          <button type="submit" :disabled="form.processing"
            class="btn btn-primary mt-8 flex w-full items-center justify-center text-center">
            <img v-if="form.processing" src="/assets/images/ajax_loading_white.svg" alt="" />
            {{ trans('Resend Verification Email') }}
          </button>
          <Link class="w-full rounded-md border p-3 text-red-600 hover:text-red-700" :href="route('logout')"
            method="post" as="button">
          {{ trans('Logout') }}
          </Link>
        </form>
      </div>
    </div>
  </div>
</template>
