require('./bootstrap');

// Require Vue
window.Vue = require('vue').default;

// Init own global variable
Vue.prototype.$models = {};

// Register own models & plugins
import ProductModel from "./models/ProductModel";
import UtilsPlugin from "./utils";
Vue.use(ProductModel);
Vue.use(UtilsPlugin);

// Register Vue Components
Vue.component('template-pizza-card-component', require('./components/templates/pizza/TemplatePizzaCardComponent.vue').default);

// router in single page web-application
import routes from './routes';

// Initialize Vue
const app = new Vue({
    el: '#app',
    router: routes,
});