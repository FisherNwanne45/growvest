<script setup>
import DefaultHeader from "@/Layouts/Default/Header.vue"
import DefaultFooter from "@/Layouts/Default/Footer.vue"
import ToastrContainer from "@/Components/ToastrContainer.vue"
import ValidationErrors from "@/Components/Admin/ValidationErrors.vue"
import Seo from "@/Components/Seo.vue"
</script>

<template>
  <Seo />
  <DefaultHeader />
  <ToastrContainer />
  <ValidationErrors />

  <slot />
  <div class="rt-mobile-menu-overlay lg:hidden"></div>
  <DefaultFooter />
</template>

<style lang="scss">
.toastify {
  @apply flex items-center gap-2 rounded-primary border border-slate-200 bg-white bg-none text-slate-700 shadow-lg dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300;

  &-primary {
    @apply border-primary-500 bg-primary-500 text-white dark:border-primary-500 dark:bg-primary-500;
  }

  &-secondary {
    @apply border-secondary-500 bg-secondary-500 text-white dark:border-secondary-500 dark:bg-secondary-500;
  }

  &-success {
    @apply border-success-500 bg-success-500 text-white dark:border-success-500 dark:bg-success-500;
  }

  &-danger {
    @apply border-danger-500 bg-danger-500 text-white dark:border-danger-500 dark:bg-danger-500;
  }

  &-warning {
    @apply border-warning-500 bg-warning-500 text-white dark:border-warning-500 dark:bg-warning-500;
  }

  &-info {
    @apply border-info-500 bg-info-500 text-white dark:border-info-500 dark:bg-info-500;
  }

  &-dark {
    @apply border-dark-500 bg-dark-500 text-white dark:border-dark-500 dark:bg-dark-500;
  }
}

.toast-close {
  @apply text-xs;
}
</style>
