import Vue from 'vue';
import VueRouter from 'vue-router';
import PostList from '../components/PostList.vue';
import Post from '../components/Post.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    component: PostList,
    name: 'post.index'
  },
  {
    path: '/posts/:post_slug/:post_id',
    component: Post,
    name: 'post.show',
    props: true
  },
  {
    path: '*',
    redirect: '/'
  }
];

export default new VueRouter({
  routes,
  mode: 'history',
  linkActiveClass: 'active',
});;