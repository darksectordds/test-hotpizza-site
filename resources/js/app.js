require('./bootstrap');

// Require Vue
window.Vue = require('vue').default;

// Axios
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios, axios);

// FontAwesome
import { library } from '@fortawesome/fontawesome-svg-core'; // import the fontawesome core
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'; //import font awesome icon component
import { faCartShopping } from '@fortawesome/free-solid-svg-icons'; //add some free styles
library.add(faCartShopping); //add icons to the library
Vue.component('font-awesome-icon', FontAwesomeIcon); // add font awesome icon component

Vue.config.productionTip = false;

// Init own global variable
Vue.prototype.$models = {};

// Register own models & plugins
import ProductModel from "./models/ProductModel";
import ProductPhotoModel from "./models/ProductPhotoModel";
import UtilsPlugin from "./utils";
Vue.use(ProductModel);
Vue.use(ProductPhotoModel);
Vue.use(UtilsPlugin);

// Register Vue Components
Vue.component('app-component', require('./components/modules/AppComponent').default);

// router in single page web-application
import routes from './routes';

// Initialize Vue
const app = new Vue({
    el: '#app',
    router: routes,
});