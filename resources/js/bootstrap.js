/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios'
window.axios = axios

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

import Echo from 'laravel-echo'

// import Pusher from 'pusher-js'
// window.Pusher = Pusher

// const pusherConfig = {
//     broadcaster: 'pusher',
//     key: 'agro',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? '127.0.0.1',
//     wsPort: '6001',
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: false,
//     disableStats: true
// }

// window.Echo = new Echo(pusherConfig)
