/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
//import router from './routes'
//window.Vue = require('vue').default;
//const fs = require('fs')
//require('dotenv-expand')(require('dotenv').config()); 

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import '@mdi/font/css/materialdesignicons.css'
import { aliases, mdi } from 'vuetify/lib/iconsets/mdi'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'


const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        }
    },
  })



import store from './store'
import { createApp } from 'vue';
const app = createApp({});

// You can register your components globally like this also
// Add as many components as you want.

app.component('example-component', require('./components/ExampleComponent.vue').default)
app.component('home', require('./components/Home.vue').default)
app.component('navigation', require('./components/layouts/Navigation.vue').default)
app.component('login-register', require('./components/layouts/LoginRegister.vue').default)
app.component('app', require('./components/App.vue').default)
// In your case 
//app.component('my-button',require('Path to the component').default);
app.use(vuetify);
app.use(store);
//app.use(VueViewer);
//app.use(router);
app.mount('#app');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/* const app = new Vue({
    el: '#app',
});
 */

