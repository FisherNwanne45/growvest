<script setup>
import { onMounted } from "vue";

import AdminLayout from "@/Layouts/Admin.vue";
import PageHeader from "@/Layouts/Admin/PageHeader.vue";
import About from "@/Pages/Admin/PageSetting/About.vue";
import Auth from "@/Pages/Admin/PageSetting/Auth.vue";
import Contact from "@/Pages/Admin/PageSetting/Contact.vue";
import Home from "@/Pages/Admin/PageSetting/Home.vue";
import Primary from "@/Pages/Admin/PageSetting/Primary.vue";
import tabs from "@/Plugins/Admin/tabs";

defineOptions({ layout: AdminLayout })
onMounted(() => {
  tabs.init()
})

const props = defineProps(["primary_data", "home_page", "about_page", 'contact_page', 'auth_page'])
</script>

<template>
  <main class="container flex-grow p-4 sm:p-6">
    <PageHeader />

    <div class="space-y-6">
      <div class="card">
        <div class="card-body">
          <div class="tabs tabs-vertical">
            <ul class="tabs-list w-72">
              <li class="tabs-item">
                <button class="tabs-btn active" data-panel-id="#primary" type="button">
                  <span>{{ trans("Primary") }}</span>
                </button>
              </li>
              <li class="tabs-item">
                <button class="tabs-btn" data-panel-id="#home" type="button">
                  <span>{{ trans("Home Page") }}</span>
                </button>
              </li>

              <li class="tabs-item">
                <button class="tabs-btn" data-panel-id="#about" type="button">
                  <span>{{ trans("About Page") }}</span>
                </button>
              </li>
              <li class="tabs-item">
                <button class="tabs-btn" data-panel-id="#contact" type="button">
                  <span>{{ trans("Contact Page") }}</span>
                </button>
              </li>
              <li class="tabs-item">
                <button class="tabs-btn" data-panel-id="#auth" type="button">
                  <span>{{ trans("Auth Pages") }}</span>
                </button>
              </li>
            </ul>

            <div class="tabs-content">
              <div class="tabs-panel active" id="primary">
                <Primary :data="primary_data" />
              </div>

              <div class="tabs-panel" id="home">
                <Home :data="home_page" />
              </div>

              <div class="tabs-panel" id="about">
                <About :data="about_page" />
              </div>

              <div class="tabs-panel" id="contact">
                <Contact :data="contact_page" />
              </div>

              <div class="tabs-panel" id="auth">
                <Auth :data="auth_page" />
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
