import Vue from 'vue/dist/vue';
import store  from './store/store'
import VueRouter from 'vue-router';

import Home from './components/Home.vue';
import Pannellum from './components/Pannellum.vue';
import Vote from './components/Vote.vue';
import Registration from './components/Registration.vue';
import Users from './components/Users.vue';
import Login from './components/Login.vue';
import BoothMan from './components/BoothMan.vue';
import Gallery from './components/Gallery.vue';
import NotFound from './components/NotFound.vue';
import SponsorPage from './components/SponsorPage.vue';
import Meeting from './components/Meeting.vue';
import MeetingZoom from './components/Meeting/Zoom.vue';

Vue.use(VueRouter);


export const routes = [
    {
        name: 'home',
        path: '/',
        component: Pannellum,
        props: true,
        meta: {
            requiresAuth: true,
         }
    },
    {
        name: 'sponsors',
        path: '/sponsors/:id',
        component: SponsorPage,
        props: true,
        meta: {
            requiresAuth: true,
         }


    },
    {
        name: 'vote',
        path: '/vote',
        component: Vote,
        meta: {
            requiresAuth: true,
         }


    },
    {
        name: 'meeting',
        path: '/meeting',
        component: Meeting,
        // props: true


    },
    // {
    //     name: 'home',
    //     path: '/',
    //     component: Home,
    //     meta: {
    //         requiresAuth: true,
    //      }
    // },
    {
        name: 'registration',
        path: '/registration',
        component: Registration,
        meta: {
            requiresAuth: true,
            requireCanCreateUser: true
         }
    },
    {
        name: 'users',
        path: '/users',
        component: Users,
        meta: {
            requiresAuth: true,
         }
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta: {
            requiresAuth: false,
         }
    },
    {
        name: 'boothman',
        path: '/boothman',
        component: BoothMan,
        meta: {
            requiresAuth: true,
            requireCanManageBooth: true
         }
    },
    {
        name: 'gallery',
        path: '/gallery',
        component: Gallery,
        meta: {
            requiresAuth: true,
            is_admin : true
         }
    },
    {
        name: 'notFound',
        path: '/404',
        component: NotFound,
    },
    {
        name: 'zoom-test',
        path: '/zoom-test',
        component: MeetingZoom,
    },
];

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

router.beforeEach((to, from, next) => {
    // console.log(to.matched.some(record => record.meta.requireCanCreateUser))
    if(to.matched.some(record => record.meta.requiresAuth)){
        if(localStorage.getItem('access_token') == null){
            next({
                name: 'login'
            })
        }else{
            if(to.matched.some(record => record.meta.requireCanCreateUser)){
                if(store.getters.permissions.includes('manage user')){
                    next()
                }else{
                    next({
                        name: 'notFound'
                    })
                }
            }else if(to.matched.some(record => record.meta.requireCanManageBooth)){
                if(store.getters.permissions.includes('manage booth')){
                    next()
                }else{
                    next({
                        name: 'notFound'
                    })
                }
            }

            else{
                next()
            }
        }
    }else{
        next()
    }
});

export default router;
