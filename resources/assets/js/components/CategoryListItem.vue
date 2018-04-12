<template>
  <div>
    <img class="image" :src="category.image" />
    <div class="label">{{category.title}}</div>
    <p>{{category.description}}</p>
  </div>
</template>

<script>
export default {
  props: ['category']
}
</script>

<style scoped>

</style>
