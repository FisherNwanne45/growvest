<script setup>
import { onMounted } from "vue"
import Sidebar from "@/Layouts/User/Sidebar.vue"
import Header from "@/Layouts/User/Header.vue"
import ToastrContainer from "@/Components/ToastrContainer.vue"

import "@fortawesome/fontawesome-free/js/all"
import feather from "feather-icons"
import ResizeObserver from "resize-observer-polyfill"
import { onUpdated } from "vue"
import Modal from "@/Components/Admin/Modal.vue"
import Dropdown from "@/Plugins/Admin/dropdown"
import ValidationErrors from "@/Components/Admin/ValidationErrors.vue"

onMounted(() => {
  Dropdown.init()
  feather.replace()
  window.ResizeObserver = ResizeObserver
})

onUpdated(() => {
  Dropdown.init()
  feather.replace()
})
</script>

<template>
  <div id="app">
    <Modal />
    <ToastrContainer />
    <!-- Sidebar Starts -->
    <Sidebar />
    <!-- Sidebar Ends -->

    <!-- Wrapper Starts -->
    <div class="wrapper">
      <!-- Header Starts -->
      <Header />
      <!-- Header Ends -->

      <ValidationErrors />

      <!-- Page Content Starts -->
      <div class="content">
        <!-- Main Content Starts -->
        <slot></slot>
        <!-- Main Content Ends -->
      </div>
      <!-- Page Content Ends -->
    </div>
    <!-- Wrapper Ends -->
  </div>
</template>
