<template>
	<div class="w3-content" style="max-width:1400px">
		<app-header></app-header>
		<router-view></router-view>
		<app-footer></app-footer>
	</div>
</template>

<script>
import AppHeader from './partials/AppHeader.vue';
import AppFooter from './partials/AppFooter.vue';

export default {
  components : {
  	AppHeader, AppFooter
  }
}
</script>

<style scoped>

</style>