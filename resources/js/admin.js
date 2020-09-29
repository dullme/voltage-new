/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.component('harness-create', require('./components/CreateHarnessComponent').default);
Vue.component('typical-create', require('./components/CreateTypicalComponent').default);
Vue.component('project-create', require('./components/CreateProjectComponent').default);
Vue.component('project-info', require('./components/ProjectInfoComponent').default);
Vue.component('quotation-info', require('./components/QuotationInfoComponent').default);
Vue.component('whip-info', require('./components/WhipInfoComponent').default);
Vue.component('test', require('./components/TestComponent').default);
