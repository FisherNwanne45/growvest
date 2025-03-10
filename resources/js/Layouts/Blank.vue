<script setup>
import ValidationErrors from "@/Components/Admin/ValidationErrors.vue";
import Seo from "@/Components/Seo.vue";
</script>

<template>
  <Seo />
  <ValidationErrors />
  <slot></slot>
</template>


