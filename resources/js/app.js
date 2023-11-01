import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import ExchangeComponent from './components/ExchangeComponent.vue' 
 
createApp({}) 
    .component('ExchangeComponent', ExchangeComponent)
    .mount('#app') 
