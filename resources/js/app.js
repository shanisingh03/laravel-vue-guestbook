require('./bootstrap');
// window.Vue = require('vue');
var Vue = require('vue');

var VueRouter = require('vue-router');
Vue.use(VueRouter)




// Routes Define Here
let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue') },
    { path: '/profile', component: require('./components/Profile.vue') }
]



// Register Routes Here
const router = new VueRouter({
    routes // short for `routes: routes`
});

const app = new Vue({
    el: "#app",
    router
});
