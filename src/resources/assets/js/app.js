window.Vue = require('vue');

Vue.component('s-app', require('./components/App.vue').default);
Vue.component('s-wrapper', require('./components/Wrapper.vue').default);
Vue.component('s-action', require('./components/Action.vue').default);
Vue.component('s-svg', require('./components/Svg.vue').default);

const app = new Vue({
  el: '#app',
  created() {
    var vm = this
  },
  data: {
    blocks: [],
    content: window.App.content,
    body: window.App.body,
    settings: window.App.settings,
    user: window.App.user,
    errors: window.App.errors
  },
  beforeMount() {
    let data = JSON.parse(this.body.data);
    this.blocks = data.data;
  }
});
