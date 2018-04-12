<template>
   <div>
      <h3>Post a comment (as a guest)</h3>
      <form  v-on:submit.prevent class="w3-container">
         <br>
         <p>      
            <label class="w3-text-grey">Name</label>
            <input class="w3-input w3-border" v-model="comment.user.name" type="text" required>
         </p>
         <p>      
            <label class="w3-text-grey">Email</label>
            <input class="w3-input w3-border"  v-model="comment.user.email" type="email" required>
         </p>
         <p>      
            <label class="w3-text-grey">Message</label>
            <textarea class="w3-input w3-border"  v-model="comment.body"  style="resize:none" required></textarea>
         </p>
         <p>
            <button class="w3-button w3-black"  @click="saveGuestComment">Submit</button>
         </p>
      </form>
   </div>
</template>

<script>
import gql from 'graphql-tag';

const commentAsGuestMutation = gql`
  mutation commentAsGuestMutation($post_id : Int!, $body : String!, $user : CommentUserInput!) {
    comment : commentAsGuest( post_id : $post_id, body : $body, user : $user){
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
  props: ['post_id'],
  data(){
    return {
    	comment : {
	      body : '',
	      user : {
	        name : '',
	        email : ''
	      }
	    },
  	}
  },
  methods : {
  	reset(){
		  Object.assign(this.$data, this.$options.data.call(this));
  	},
    saveGuestComment(){
      this.$apollo.mutate({
        mutation : commentAsGuestMutation,
        variables: {
          post_id : this.post_id,
          body : this.comment.body,
          user : this.comment.user
        },
        fetchPolicy : 'no-cache'
      })
      .then((response) => {
        let comment = JSON.parse(JSON.stringify(response.data.comment));
      	this.$emit('save', comment);
   		  this.reset();
      })
      .catch((error) => {
        console.error(error);
      });
    }
  }
}
</script>

<style scoped>

</style>
