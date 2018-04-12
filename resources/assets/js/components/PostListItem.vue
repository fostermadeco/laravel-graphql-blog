<template>
   <div class="w3-card-4 w3-margin w3-white">
      <router-link :to="{ name : 'post.show' ,  params :  { post_slug : post.slug, post_id : post.id } }">
         <img :src="post.image" alt="" style="width:100%">
      </router-link>
      <div class="w3-container">
         <h3>{{post.title}}</h3>
         <h5>{{post.description}}<span class="w3-opacity"> {{post.created_at |  moment("dddd, MMMM Do YYYY") }}</span></h5>
      </div>
      <div class="w3-container">
         <p>{{post.description}}</p>
         <div class="w3-row">
            <div class="w3-col m8 s12">
               <p>
                  <router-link tag="button" :to="{ name : 'post.show' ,  params :  { post_slug : post.slug, post_id : post.id } }"  type="button" class="w3-button w3-padding-large w3-white w3-border">
                     Read More »
                  </router-link>
               </p>
            </div>
            <div class="w3-col m4 w3-hide-small">
               <p><span class="w3-padding-large w3-right"><b>Comments</b> <span class="w3-tag">{{post.comment_count}}</span></span></p>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
export default {
  props: ['post']
}
</script>

<style scoped>

</style>
