<script setup>
import sharedComposable from '@/Composables/sharedComposable'
import { onMounted, ref } from 'vue'
// import SearchModal from '@/Plugins/Admin/search-modal'
import themeSwitcher from '@/Plugins/Admin/themeSwitcher'
import { computed } from 'vue'
import trans from '@/Composables/transComposable'
import { usePage } from '@inertiajs/vue3'
const { authUser, logout } = sharedComposable()

onMounted(() => {
  themeSwitcher.init()
  modal.value = createModal(document.getElementById('search-modal'))
})

// notification
const notifications = usePage().props.notifications ?? []

const unreadNotifications = computed(() => {
  return notifications.filter((item) => item.seen == 0).length ?? 0
})

const maskAsRead = (item) => {
  axios.post(route('user.notifications.read', item.id)).then((res) => {
    item.seen = 1
  })
}

// search modal
const modal = ref({})
const search = ref('')
const menuGroups = [
  {
    title: 'General',
    pages: [
      {
        icon: 'bx bx-home',
        text: 'Dashboard',
        uri: route('user.dashboard')
      },
      {
        icon: 'bx bx-folder-minus',
        text: 'Deposits',
        uri: route('user.wallet-transactions.index')
      },
      {
        icon: 'bx bx-bar-chart',
        text: 'Profit Return',
        uri: route('user.profit-return')
      },
      {
        icon: 'bx bx-bar-chart',
        text: 'Investment Plan',
        uri: route('user.projects.index')
      },
      {
        icon: 'bx bx-cart',
        text: 'Investments History',
        uri: route('user.investments.index')
      },
      {
        icon: 'bx bx-cart-alt',
        text: 'Event Orders',
        uri: route('user.event-orders.index')
      },
      {
        icon: 'bx bx-phone-call',
        text: 'Supports',
        uri: route('user.supports.index')
      },
      {
        icon: 'bx bx-dollar',
        text: 'Payouts',
        uri: route('user.payout.index')
      },
      {
        icon: 'bx bx-cog',
        text: 'Account Settings',
        uri: route('user.account-settings')
      },
      {
        icon: 'bx bx-user',
        text: 'Affiliate Program',
        uri: route('user.affiliate.index')
      },
      {
        icon: 'bx bx-file',
        text: 'KYC Verification',
        uri: route('user.kyc.index')
      }
    ]
  }
]
const filteredMenuItems = computed(() => {
  return (
    menuGroups
      .map((item) => {
        return {
          ...item,
          // Search the pages array for pages that start with the search string
          pages: item.pages
            .filter((page) => page.text?.toLowerCase().startsWith(search.value?.toLowerCase()))
            .map((item) => {
              item.text = trans(item.text)
              return item
            })
        }
      })
      // Filter out items that have no pages
      .filter((item) => item.pages.length > 0)
  )
})
</script>

<template>
  <!-- Top nav -->
  <header class="header">
    <div class="container-fluid flex items-center justify-between">
      <!-- Sidebar Toggle & Search Starts -->
      <div class="flex items-center space-x-6">
        <button class="sidebar-toggle">
          <span class="flex space-x-4">
            <svg
              stroke="currentColor"
              fill="none"
              stroke-width="0"
              viewBox="0 0 24 24"
              height="22"
              width="22"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h8m-8 6h16"
              ></path>
            </svg>
          </span>
        </button>

        <!-- Mobile Search Starts -->
        <div class="sm:hidden">
          <button
            type="button"
            @click="modal.show()"
            class="flex items-center justify-center rounded-full text-slate-500 transition-colors duration-150 hover:text-primary-500 focus:text-primary-500 dark:text-slate-400 dark:hover:text-slate-300"
          >
            <i width="22" height="22" data-feather="search"></i>
          </button>
        </div>
        <!-- Mobile Search Ends -->

        <!-- Searchbar Start -->
        <button
          type="button"
          @click="modal.show()"
          class="group hidden h-10 w-72 items-center overflow-hidden rounded-primary bg-slate-100 px-3 shadow-sm dark:border-transparent dark:bg-slate-700 sm:flex"
        >
          <i class="text-slate-400" width="1em" height="1em" data-feather="search"></i>
          <span class="ml-2 text-sm text-slate-400">Search</span>
        </button>
        <!-- Searchbar Ends -->
      </div>
      <!-- Sidebar Toggle & Search Ends -->

      <!-- Header Options Starts -->

      <div class="flex items-center">
        <!-- Language Dropdown Starts -->
        <div class="dropdown" data-strategy="absolute" v-if="$page.props?.languages?.length">
          <div class="dropdown-toggle px-3">
            <button
              type="button"
              class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-700 focus:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:text-slate-300"
            >
              <span class="hidden font-medium md:inline-block">
                {{ $page.props?.languages[$page.props?.locale] }}</span
              >
              <span class="inline-block font-medium md:hidden">
                {{ $page.props?.locale?.toUpperCase() }}</span
              >
            </button>
          </div>

          <div class="dropdown-content mt-3 w-40">
            <ul class="dropdown-list">
              <li
                v-for="(language, key) in $page.props.languages"
                :key="key"
                class="dropdown-list-item"
              >
                <Link
                  as="button"
                  :href="route('set-locale', key)"
                  method="patch"
                  class="dropdown-btn"
                >
                  <span class="">{{ language }}</span>
                </Link>
              </li>
            </ul>
          </div>
        </div>
        <!-- Language Dropdown Ends -->

        <!-- Dark Mood Toggle Starts -->
        <div class="dropdown" data-strategy="absolute" id="theme-switcher-dropdown">
          <button
            class="dropdown-toggle px-3 text-slate-500 hover:text-slate-700 focus:text-primary-500 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:text-primary-500"
            type="button"
          >
            <i class="hidden dark:block" width="24" height="24" data-feather="moon">Dark</i>
            <i class="block dark:hidden" width="24" height="24" data-feather="sun">Light</i>
          </button>

          <div class="dropdown-content mt-3 w-36">
            <ul class="dropdown-list">
              <li class="dropdown-list-item">
                <button type="buttton" class="dropdown-btn" data-theme-mode="light">
                  <i width="16" height="16" data-feather="sun"></i>
                  <span>Light</span>
                </button>
              </li>

              <li class="dropdown-list-item">
                <button type="buttton" class="dropdown-btn" data-theme-mode="dark">
                  <i width="16" height="16" data-feather="moon"></i>
                  <span>Dark</span>
                </button>
              </li>

              <li class="dropdown-list-item">
                <button type="buttton" class="dropdown-btn" data-theme-mode="system">
                  <i width="16" height="16" data-feather="monitor"></i>
                  <span>System</span>
                </button>
              </li>
            </ul>
          </div>
        </div>
        <!-- Dark Mood Toggle Ends -->

        <!-- Notification Dropdown Starts -->
        <div class="dropdown -mt-0.5" data-strategy="absolute">
          <div class="dropdown-toggle px-3">
            <button
              class="relative mt-1 flex items-center justify-center rounded-full text-slate-500 transition-colors duration-150 hover:text-slate-700 focus:text-primary-500 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:text-primary-500"
            >
              <i width="24" height="24" data-feather="bell"></i>
              <span
                v-if="unreadNotifications"
                class="absolute -right-1 -top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-danger-500 text-[11px] text-slate-200"
              >
                {{ unreadNotifications }}
              </span>
            </button>
          </div>

          <div class="dropdown-content mt-3 w-[17.5rem] divide-y dark:divide-slate-700 sm:w-80">
            <div class="flex items-center justify-between px-4 py-4">
              <h6 class="text-slate-800 dark:text-slate-300">
                {{ trans('Notifications') }}
              </h6>
              <button
                class="text-xs font-medium text-slate-600 hover:text-primary-500 dark:text-slate-300"
                v-if="notifications.length"
              >
                {{ trans('Clear All') }}
              </button>
            </div>

            <div class="h-80 w-full" data-simplebar>
              <ul>
                <template v-if="notifications.length">
                  <li
                    v-for="(item, index) in notifications"
                    :key="index"
                    @click="maskAsRead(item)"
                    class="flex cursor-pointer gap-4 px-4 py-3 transition-colors duration-150 hover:bg-slate-100/70 dark:hover:bg-slate-700"
                  >
                    <div
                      class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-500"
                    >
                      <i class="bx bx-user-voice" width="20" height="20"></i>
                    </div>

                    <a :href="item.url ?? '#'">
                      <h6 class="text-sm font-normal">{{ item.title }}</h6>
                      <p class="text-xs text-slate-400" :title="item.comment">
                        {{ item.comment_short }}
                      </p>
                      <p class="mt-1 flex items-center gap-1 text-xs text-slate-400">
                        <i data-feather="clock" width="1em" height="1em"></i>
                        <span>{{ item.created_at_human_date }}</span>
                      </p>
                    </a>
                  </li>
                </template>
                <li v-else class="mt-5 text-center">{{ trans('no notifications') }}</li>
              </ul>
            </div>

            <div class="px-4 py-2" v-if="notifications.length > 5">
              <Link :href="route('user.notifications')" class="btn btn-primary-plain btn-sm w-full">
                <span>{{ trans('View More') }}</span>
                <i data-feather="arrow-right" width="1rem" height="1rem"></i>
              </Link>
            </div>
          </div>
        </div>
        <!-- Notification Dropdown Ends -->

        <!-- Profile Dropdown Starts -->
        <div class="dropdown" data-strategy="absolute">
          <div class="dropdown-toggle pl-3">
            <button class="group relative flex items-center gap-x-1.5" type="button">
              <div class="avatar avatar-circle avatar-indicator avatar-indicator-online">
                <img
                  class="avatar-img group-focus-within:ring group-focus-within:ring-primary-500"
                  v-lazy="
                    authUser.avatar == null
                      ? `https://ui-avatars.com/api/?name=${authUser.name}`
                      : `${authUser.avatar}`
                  "
                  :alt="authUser.name"
                />
              </div>
            </button>
          </div>

          <div class="dropdown-content mt-1 w-56 divide-y dark:divide-slate-600">
            <div class="px-4 py-3">
              <p class="text-sm">
                Welcome <strong>{{ authUser.name }}</strong
                >!
              </p>
              <p class="truncate text-sm font-medium">({{ authUser.email }})</p>
            </div>
            <div class="py-1">
              <Link href="/user/account-settings" class="dropdown-link">
                <i width="18" height="18" data-feather="user" class="text-slate-500"></i>
                <span>{{ trans('Account Settings') }}</span>
              </Link>
            </div>
            <div class="py-1">
              <form method="POST" action="#">
                <button type="button" @click="logout()" class="dropdown-btn">
                  <i width="18" height="18" data-feather="log-out" class="text-slate-500"></i>
                  <span>{{ trans('Logout') }}</span>
                </button>
              </form>
            </div>
          </div>
        </div>
        <!-- Profile Dropdown Ends -->
      </div>
      <!-- Header Options Ends -->
    </div>
  </header>
  <!-- Header -->

  <!-- Search Modal Start -->
  <div class="modal" id="search-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header px-4 sm:px-6">
          <div class="group flex items-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-search text-slate-500 group-focus-within:text-slate-600 dark:text-slate-400 dark:group-focus-within:text-slate-300"
            >
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg
            ><input
              type="text"
              v-model="search"
              class="w-full border-none bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:ring-0 dark:text-slate-200"
              placeholder="Search"
            /><button
              class="rounded-primary bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600"
              data-dismiss="modal"
            >
              ESC
            </button>
          </div>
        </div>
        <div class="modal-body max-h-[600px] px-4 py-6 sm:px-6" data-simplebar="init">
          <div class="-mt-[12px] space-y-4">
            <div v-for="(group, index) in filteredMenuItems" :key="index" class="">
              <h6>{{ group.title }}</h6>
              <ul class="mt-2 space-y-2">
                <li v-for="(page, index) in group.pages" :key="index" class="">
                  <Link
                    @click="modal.hide()"
                    :href="page.uri || '#'"
                    class="flex items-center gap-2 rounded-primary bg-slate-50 px-4 py-2 text-sm shadow-sm hover:bg-slate-100 dark:bg-slate-700 dark:hover:bg-slate-600"
                  >
                    <i class="text-lg text-slate-500 dark:text-slate-400" :class="page.icon"></i>
                    <span>{{ page.text }}</span>
                    <i class="ti ti-chevron-right ml-auto text-slate-500"></i>
                  </Link>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop"></div>
  </div>
  <!-- Search Modal Ends -->
</template>
