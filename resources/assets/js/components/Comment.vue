<template>
  <li class="w3-padding-16" style="height:80px">
      <img :src="comment.user.avatar" alt="Image" class="w3-left w3-margin-right" style="width:50px">
      <b>{{comment.user.name}}</b> - {{comment.created_at}}
      <br/>
      <span>{{comment.body}}</span>
  </li>
</template>

<script>
export default {
  props: ['comment']
}
</script>

<style scoped>

</style>
