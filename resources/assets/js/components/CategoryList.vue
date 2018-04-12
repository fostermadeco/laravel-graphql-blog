<template>
  <div>
    <template v-if="loading > 0">
      Loading ...
    </template>
    <template v-else>
      <center><h3 class="title">Hey There</h3></center>
      <category-list-item v-for="category in categories" v-bind:key="category.id" v-bind:category="category"></category-list-item>
    </template>
  </div>
</template>

<script>
import gql from 'graphql-tag';
import PostListItem from './PostListItem.vue';

const categoriesQuery = gql`
  query categoriesPerPageQuery{
    categories {
      id
      title
      description
      image
      user {
        id
        name
        avatar
      }
    }
  }
`;

export default {
  components: {
    PostListItem
  },
  data: () => ({
    categories : [],
    loading: 0,
  }),
  apollo: {
    $loadingKey: 'loading',
    categories: {
      query: categoriesQuery
    }
  },
};
</script>

<style scoped>

</style>