<template>
   <div class="w3-row">
      <div class="w3-col l8 s12">
         <template v-if="loading > 0">
            Loading ...
         </template>
         <template v-else>
            <div class="w3-card-4 w3-margin w3-white">
               <img :src="post.image" alt="" style="width:100%">
               <div class="w3-container">
                  <h1>{{post.title}}</h1>
                  <p>{{post.description}}</p>
                  <span class="w3-opacity"> {{post.created_at |  moment("dddd, MMMM Do YYYY") }}</span>
               </div>
               <div class="w3-container">
                  <p>{{post.body}}</p>
               </div>
               <hr/>
               <div class="w3-margin">
                  <h3>Comments</h3>
                  <template v-if="comments">
                     <comment v-for="comment in comments" :key="comment.id" :comment="comment"></comment>
                  </template>
                  <template v-else>
                     <p>There are no comments available.</p>
                  </template>
                  <hr/>
                  <guest-comment-form :post_id="post_id" @save="addNewComment" ></guest-comment-form>
               </div>
            </div>
         </template>
      </div>
      <div class="w3-col l4">
         <div class="w3-card w3-margin">
            <div class="w3-container w3-padding">
               <h4>Related Posts</h4>
            </div>
            <ul  v-if="post.related_posts.length > 0"  class="w3-ul w3-hoverable w3-white">
               <related-post-list-item v-for="post in post.related_posts" :key="post.id" :post="post"></related-post-list-item>
            </ul>
            <p v-else>
               There are no related posts available
            </p>
         </div>
         <hr>
      </div>
   </div>
</template>

<script>
import gql from 'graphql-tag';
import Comment from './Comment.vue';
import RelatedPostListItem from './RelatedPostListItem.vue';
import GuestCommentForm from './GuestCommentForm.vue';

const postQuery = gql`
  query postQuery($id : Int!) {
    post(id : $id) {
      id
      title
      body
      image (width: 1024, height: 512)
      view_count
      comment_count
      related_posts(limit: 5){
        id slug title description image(width: 300, height: 300) created_at
      }
    }
  }
`;

const commentsQuery = gql`
  query commentsQuery($post_id : Int!) {
    comments( post_id : $post_id){
      id 
      body
      created_at(diffForHumans : true)
      user {
        name avatar
      }
    }
  }
`;

export default {
  props: ['post_slug','post_id'],
  data: () => ({
    post : {
      id : '',
      title : '',
      body : '',
      image : '',
      comment_count : 0,
      view_count : 0,
      related_posts : [],
    },
    comments : [],
    loading: 0
  }),
  apollo: {
    post: {
      query: postQuery,
      variables () {
        return { id : this.post_id };
      },
      loadingKey: 'loading'
    },
    comments: {
      query: commentsQuery,
      variables () {
        return { post_id : this.post_id };
      },
      update(data) {
        return JSON.parse(JSON.stringify(data.comments));
      },
      loadingKey: 'loading'
    }
  },
  computed: {

  },
  components: {
    RelatedPostListItem, Comment, GuestCommentForm
  },
  methods: {
    addNewComment(comment){
      this.comments.push(comment);
    }
  }
}
</script>

<style scoped>

</style>
