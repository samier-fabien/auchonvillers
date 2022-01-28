import Vue from 'vue';
import App from '../pages/home';

Vue.filter('lowercase', function(value) {
  return value.toLowerCase();
});
Vue.filter('date', function(value) {
  return new Date(value);
});
Vue.filter('dateConvert', function(value) {
  return new Date(value).toLocaleDateString();
});

new Vue({
    // delimiters: ['${', '}$'],
    render(h) {
        return h(App, {
          props: {
            machin: this.$el.getAttribute('data-name'),
            newsletters: JSON.parse(this.$el.getAttribute('data-newsletters'))
          },
        })
      },
}).$mount('#app');
