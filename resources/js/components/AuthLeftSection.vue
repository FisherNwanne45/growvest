<script setup>
const props = defineProps(['data'])
</script>

<template>
    <ul class="list-item space-y-6 pt-8">
        <li class="flex">
            <div class="mr-6 flex-none">
                <div class="">
                    <img src="/assets/images/svg/mail.svg" alt="" class="" />
                </div>
            </div>
            <div class="flex-1">
                <h4 class="mb-1 text-lg lg:text-xl">{{ trans('Email-Us') }} :</h4>
                <div>{{ data.email }}</div>
            </div>
        </li>
        <li class="flex">
            <div class="mr-6 flex-none">
                <div class="">
                    <img src="/assets/images/svg/call.svg" alt="" class="" />
                </div>
            </div>
            <div class="flex-1">
                <h4 class="mb-1 text-lg lg:text-xl">{{ trans('Call Us') }}:</h4>
                <div>{{ data.phone }}</div>
            </div>
        </li>
        <li class="flex">
            <div class="mr-6 flex-none">
                <div class="">
                    <img src="/assets/images/svg/map.svg" alt="" class="" />
                </div>
            </div>
            <div class="flex-1">
                <h4 class="mb-1 text-lg lg:text-xl">{{ trans('Office') }} :</h4>
                <div>{{ data.address }}</div>
            </div>
        </li>
    </ul>
</template>