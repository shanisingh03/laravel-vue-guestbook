// Require Bootstrap
require("./bootstrap");

// Initialize Vue
window.Vue = require("vue");

// Register Passport Component
Vue.component(
    "passport-clients",
    require("./components/passport/Clients.vue").default
);

Vue.component(
    "passport-authorized-clients",
    require("./components/passport/AuthorizedClients.vue").default
);

Vue.component(
    "passport-personal-access-tokens",
    require("./components/passport/PersonalAccessTokens.vue").default
);

// Pagination
Vue.component("pagination", require("laravel-vue-pagination"));

// Vue Laravel Form Validation
import { Form, HasError, AlertError } from "vform";

// Use Form Through out Application
window.Form = Form;

// Laravel Form Global Components
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

// Import Vue Router
import VueRouter from "vue-router";
Vue.use(VueRouter);

// Import Vue Progress bar
import VueProgressBar from "vue-progressbar";

// Define Default Progress Bar
Vue.use(VueProgressBar, {
    color: "rgb(143, 255, 199)",
    failedColor: "red",
    height: "2px"
});

// Import SweatAlert
import Swal from "sweetalert2";
window.swal = Swal;

// Register A Global Toaster
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000
});

// Define Globally
window.toast = Toast;

// Register A Global Event Listner
window.Fire = new Vue();

// Register Pagination Components
Vue.component("pagination", require("laravel-vue-pagination"));

// Import Components
import Dashboard from "./components/Dashboard";
import Profile from "./components/Profile";
import Contacts from "./components/Contacts";

// Include Filters
require("./filters");

// Routes Define Here
let routes = [
    {
        path: "/",
        redirect: "/dashboard"
    },
    {
        path: "/home",
        redirect: "/dashboard"
    },
    {
        path: "/dashboard",
        component: Dashboard,
        name: "dashboard"
    },
    {
        path: "/profile",
        component: Profile,
        name: "profile"
    },
    {
        path: "/contacts",
        component: Contacts,
        name: "contacts"
    }
];

// Register Routes Here
const router = new VueRouter({
    mode: "history",
    routes // short for `routes: routes`
});

const app = new Vue({
    el: "#app",
    router,
    data: {
        AppName: "GuestBook",
        search: ""
    },
    methods: {
        searchGlobal() {
            Fire.$emit("searching");
        }
    }
});
