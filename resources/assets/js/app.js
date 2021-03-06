
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('categoria', require('./components/Categoria.vue').default);
Vue.component('articulo', require('./components/Articulo.vue').default);
Vue.component('cliente',require('./components/Cliente.vue').default);
Vue.component('proveedor',require('./components/Proveedor.vue').default);
Vue.component('rol',require('./components/Rol.vue').default);
Vue.component('user',require('./components/User.vue').default);
Vue.component('ingreso',require('./components/Ingreso.vue').default);
Vue.component('venta',require('./components/Venta.vue').default);
Vue.component('dashboard',require('./components/Dashboard.vue').default);
Vue.component('consultaingreso',require('./components/ConsultaIngreso.vue').default);
Vue.component('consultaventa',require('./components/ConsultaVenta.vue').default);


const app = new Vue({
    el: '#app',
    data:{
        menu: 0 
    }

});
