
import Vue from 'vue'
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.css'
import css from 'vue-material/dist/vue-material.css'
import style from 'vue-material/dist/vue-material.css'
import VueTruncate from 'vue-truncate'
import VueRouter from 'vue-router'
import store from './store'
import axios from 'axios' 
import VueAxios from 'vue-axios'
import Vuelidate from 'vuelidate'




Vue.use(VueMaterial)
Vue.use(VueRouter)
Vue.use(VueAxios, axios)
Vue.use(Vuelidate)


import settings from './components/settings.vue'
import mainPage from './components/mainPage.vue'
import singIn from './components/singIn.vue'
import filtration from './components/filtration.vue'
import favorite from './components/favorite.vue'
import dropDown from './components/dropDown.vue'
import post from './components/post.vue'
import contacts from './components/contacts.vue'
import myheader from './components/myheader.vue'
import registration from './components/registration.vue'



const router = new VueRouter({
    mode: 'history',
    hashbang: false,
    linkActiveClass: 'active',
    transitionOnLoad: true,
    routes: [
    { path: '/settings', component: settings},
    { path: '/singIn', component: singIn },
    { path: '/registration', component: registration},
    { path: '/contacts', component: contacts }, 
    { path: '/post/:id', name: 'post', component: post },
    { path: '/:page/', name: 'page', component: mainPage },
    { path: '/1', alias: '/', component: mainPage},
   
    

  ]})



new Vue({
  el: '#app',
  router: router,
    store,
    components: {myheader, filtration}
    
    
    
})


    
    













