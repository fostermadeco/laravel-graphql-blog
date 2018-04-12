import Vue from 'vue';
import ApolloClient from 'apollo-client';
import { HttpLink } from 'apollo-link-http';
import { InMemoryCache } from "apollo-cache-inmemory";
import VueApollo from 'vue-apollo';

const apolloClient = new ApolloClient({
  link: new HttpLink({
    uri: '/api/graphql'
  }),
  cache: new InMemoryCache(),
  connectToDevTools: true,
})

Vue.use(VueApollo, {
  apolloClient
});

export default apolloClient;
