import Vue from 'vue';
import App from '../pages/home';

new Vue({
    // delimiters: ['${', '}$'],
    render: (h) => h(App),
}).$mount('#app');
