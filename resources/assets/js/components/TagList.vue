<template>
   <div class="w3-card w3-margin">
      <div class="w3-container w3-padding">
         <h4>Tags</h4>
      </div>
      <div class="w3-container w3-white w3-padding">
         <p>
            <span v-for="tag in tags" class="w3-tag w3-light-grey w3-small w3-margin-bottom w3-margin-right">{{tag.name}}</span>
         </p>
      </div>
   </div>
</template>

<script>
export default {
  props: ['tags']
}
</script>

<style scoped>

</style>
