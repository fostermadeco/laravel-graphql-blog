<template>
  <div>
    <template v-if="loading > 0">
      Loading ...
    </template>
    <template v-else>
      <center><h3 class="title">Hey There</h3></center>
      <post-list-item v-for="post in posts" v-bind:key="post.id" v-bind:post="post"></post-list-item>
    </template>
  </div>
</template>

<script>
import gql from 'graphql-tag';
import PostListItem from './PostListItem.vue';
import Query from 'graphql-query-builder';

let userQuery = new Query("user").find( "id", "name", "avatar");
let postsQuery = new Query("posts").find(["id", "title", "description", "image", {"user": userQuery}]);

export default {
  components: {
    PostListItem
  },
  data: () => ({
    posts : [],
    loading: 0,
  }),
  apollo: {
    $loadingKey: 'loading',
    posts: {
      query:  gql`query { ${postsQuery} }`
    }
  },
};
</script>

<style scoped>

</style>
