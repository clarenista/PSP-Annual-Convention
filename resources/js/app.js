require('./bootstrap');
import Vue from 'vue/dist/vue';
import store  from './store/store'

import App from './App.vue';

import VueAxios from 'vue-axios';
import axios from 'axios';
import router from './routes';

import Permissions from './mixins/Permissions'
import SendEvent from './mixins/SendEvent'
import 'pannellum'
import CoolLightBox from 'vue-cool-lightbox'
import 'vue-cool-lightbox/dist/vue-cool-lightbox.min.css'

import Echo from "laravel-echo"

import VueHtml2Canvas from 'vue-html2canvas';

require('pannellum/build/pannellum.css')
// import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap an BootstrapVue CSS files (order is important)
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'

// Make BootstrapVue available throughout your project
// Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
// Vue.use(IconsPlugin)
Vue.mixin(Permissions);
Vue.mixin(SendEvent);
Vue.use(VueAxios, axios);
Vue.use(CoolLightBox);
Vue.use(VueHtml2Canvas);

Vue.config.productionTip = false

const app = new Vue({
    el: '#app',
    store,
    created() {
        if(localStorage.getItem('access_token') != null){
            this.getUser()
        }
        this.$store.dispatch("getBgm");
        // if(localStorage.getItem('bgmStart')){
        //     this.$store.commit('updateBgmStart', true)
        // }else{
        //     this.$store.commit('updateBgmStart', false)

        // }
    },
    render: h => h(App),
    methods: {
        async getUser(){
            let {data} = await axios.get('/api/v1/user?api_token='+localStorage.getItem('access_token'))
            store.commit('changeUser', data)
            store.commit('updateIsWelcomed', false)
        }
    },
    router,
});


// WEBSOCKET PUSH EXAMPLE

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: process.env.MIX_PUSHER_APP_HOST,
    wsPort: process.env.MIX_PUSHER_APP_PORT,
    wssPort: process.env.MIX_PUSHER_APP_PORT,
    disableStats: true,
    encrypted: false,
});

window.Echo.channel('Announcement').listen('AnnouncementEvent', (e) => {
    // CALL ANNOUNCEMENT CODE HERE
    if(e.payload.title == null){

        store.commit('changeAnnouncement', null)
    }else{
        store.commit('changeAnnouncement', e)

    }

});

// WEBSOCKET PUSH EXAMPLE END
