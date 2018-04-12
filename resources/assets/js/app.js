import Vue from 'vue';
import VueApollo from 'vue-apollo';
import App from './components/App.vue';
import router from './router';
import apolloClient from './apollo';

require('./bootstrap');

const apolloProvider = new VueApollo({
  defaultClient: apolloClient,
});

new Vue({
    el: '#root',
    router,
  	provide: apolloProvider.provide(),
  	render: h => h(App)
});
