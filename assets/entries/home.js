import Vue from 'vue';
import App from '../pages/home';

new Vue({
    // delimiters: ['${', '}$'],
    render(h) {
        return h(App, {
          props: {
            newsletters: JSON.parse(this.$el.getAttribute('data-newsletters')),
          },
        })
      },
}).$mount('#app');
