<template>
   <div class="w3-row">
      <div class="w3-col l8 s12">
         <template v-if="loading">
            Loading ...
         </template>
         <template v-else>
            <post-list-item v-for="post in postsPerPage.posts" :key="post.id" :post="post"></post-list-item>
            <paginate
               :page-count="page_count"
               :click-handler="getPostsPerPage"
               :initial-page="page - 1"
               :prev-text="'&lt;'"
               :next-text="'&gt;'"
               :container-class="'pagination w3-margin'"
               :page-class="'page-item'"
               :prev-class="'page-item'"
               :prev-link-class ="'page-link'"
               :next-class="'page-item'"
               :next-link-class ="'page-link'"
               :page-link-class ="'page-link'">
            </paginate>
         </template>
      </div>
      <div class="w3-col l4">
         <div class="w3-card w3-margin">
            <div class="w3-container w3-padding">
               <h4>Popular Posts</h4>
            </div>
            <template v-if="$apollo.loading">
               Loading...
            </template>
            <template v-else>
               <ul v-if="popularPosts.length > 0" class="w3-ul w3-hoverable w3-white">
                  <popular-post-list-item v-for="post in popularPosts" :key="post.id" :post="post"></popular-post-list-item>
               </ul>
               <p v-else>
                  There are no popular posts available
               </p>
            </template>
         </div>
         <hr>
         <tag-list :tags="tags"></tag-list>
      </div>
   </div>
</template>

<script>
import gql from 'graphql-tag';
import PostListItem from './PostListItem.vue';
import PopularPostListItem from './PopularPostListItem.vue';
import TagList from './TagList.vue';

const postsPerPageQuery = gql`
  query postsPerPageQuery($page : Int, $limit: Int){
    postsPerPage( page : $page, limit : $limit){
      total 
      per_page 
      posts : data {
        id slug title description image(width: 700, height: 220) comment_count created_at
      }
    }
  }
`;

const tagsQuery = gql`
  query tagsQuery{
    tags {
      id name
    }
  }
`;

const popularPostsQuery = gql`
  query popularPostsQuery{
    popularPosts {
      id slug title image( width: 300, height: 300) created_at
    }
  }
`;

export default {
  components: {
    PostListItem,
    PopularPostListItem,
    TagList
  },
  data: () => ({
    page : 1,
    limit : 5,
    postsPerPage : {
      total : 0,
      posts : [],
    },
    tags : [],
    popularPosts : [],
    loading: true,
  }),
  apollo: {
    tags : {
      query: tagsQuery,
    },
    popularPosts : {
      query: popularPostsQuery,
    }
  },
  mounted : function(){
    this.getPostsPerPage(this.$route.query.page || 1);
  },
  computed :{
    page_count : function(){
      return Math.ceil(this.postsPerPage.total / this.limit);
    }
  },
  methods: {
    getPostsPerPage(page){
      page = parseInt(page);
      this.$apollo.query({
        query: postsPerPageQuery,
        variables: {
          page : page,
          limit : this.limit,
        },
        fetchPolicy : 'network-only'
      })
      .then((response) => {
         response = JSON.parse(JSON.stringify(response));
         this.postsPerPage =  response.data.postsPerPage;
         this.page = page;
         this.$router.push({query : { page }});
         this.loading = false;
      })
      .catch((error) => {
        console.error(error);
      });
    }
  }
};
</script>

<style scoped>

</style>
