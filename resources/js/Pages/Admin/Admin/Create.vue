<template>
  <main class="container p-4 sm:p-6">
    <PageHeader :title="trans('Dashboard')" :segments="segments" :buttons="buttons" />
    <div class="space-y-6">
      <form @submit.prevent="form.post(route('admin.admin.store'))">
        <div class="grid grid-cols-12">
          <div class="lg:col-span-5">
            <strong>{{ trans('Create Admin') }}</strong>
            <p>{{ trans('add admin profile information') }}</p>
          </div>
          <div class="card-wrapper lg:col-span-7">
            <div class="card">
              <div class="card-body">
                <div class="mb-2">
                  <label for="name">{{ trans('Name') }}</label>
                  <input
                    type="text"
                    placeholder="Enter Name"
                    v-model="form.name"
                    class="input"
                    id="name"
                    required=""
                    autocomplete="off"
                  />
                  <div class="invalid-feedback text-danger d-block" v-if="form.errors.name">
                    {{ form.errors.name }}
                  </div>
                </div>
                <div class="mb-2">
                  <label for="email">{{ trans('Email') }}</label>
                  <input
                    type="email"
                    placeholder="Enter Email"
                    v-model="form.email"
                    name="email"
                    class="input"
                    id="email"
                    required=""
                    autocomplete="off"
                  />
                  <div class="invalid-feedback text-danger d-block" v-if="form.errors.email">
                    {{ form.errors.email }}
                  </div>
                </div>
                <div class="mb-2">
                  <label for="password">{{ trans('Password') }}</label>
                  <input
                    type="password"
                    placeholder="Enter password"
                    v-model="form.password"
                    name="password"
                    class="input"
                    id="password"
                    required=""
                    autocomplete="off"
                  />
                  <div class="invalid-feedback text-danger d-block" v-if="form.errors.password">
                    {{ form.errors.password }}
                  </div>
                </div>
                <div class="mb-2">
                  <label for="password_confirmation">{{ trans('Password') }}</label>
                  <input
                    type="password"
                    placeholder="Confirm Password"
                    v-model="form.password_confirmation"
                    name="password_confirmation"
                    class="input"
                    id="password_confirmation"
                    required=""
                    autocomplete="off"
                  />
                  <div
                    class="invalid-feedback text-danger d-block"
                    v-if="form.errors.password_confirmation"
                  >
                    {{ form.errors.password_confirmation }}
                  </div>
                </div>

                <div class="mb-2">
                  <label>{{ trans('Assign Roles') }}</label>
                  <select required v-model="form.roles" id="roles" class="select">
                    <option v-for="role in roles" :key="role.id" :value="role.name">
                      {{ role.name }}
                    </option>
                  </select>
                  <div class="invalid-feedback text-danger d-block" v-if="form.errors.roles">
                    {{ form.errors.roles }}
                  </div>
                </div>
                <div class="from-group row mt-3">
                  <div class="col-lg-12">
                    <button class="btn btn-primary">{{ trans('Create') }}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>
</template>

<script>
import AdminLayout from '@/Layouts/Admin.vue'

export default {
  layout: AdminLayout
}
</script>

<script setup>
import PageHeader from '@/Layouts/Admin/PageHeader.vue'
import { Head, useForm } from '@inertiajs/vue3'
import trans from '@/Composables/transComposable'
const props = defineProps(['segments', 'buttons', 'roles'])

const form = useForm({
  name: null,
  email: null,
  password: null,
  password_confirmation: null,
  roles: null
})

// $(".checkAll").on('click',function(){
//         $('input:checkbox').not(this).prop('checked', this.checked);
// });

// $('.group-input').on('click',function(){
//     checkPermissionByGroup($(this).data('class'),this);
// });

// function checkPermissionByGroup(className, checkThis){

//   const groupIdName = $("#"+checkThis.id);
//   const classCheckBox = $('.'+className+' input');
//   if(groupIdName.is(':checked')){
// 		  classCheckBox.prop('checked', true);
//   }else{
// 	  classCheckBox.prop('checked', false);
//   }
// }
</script>
