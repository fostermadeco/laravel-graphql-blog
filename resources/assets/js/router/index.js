import Vue from 'vue';
import VueRouter from 'vue-router';
import PostList from '../components/PostList.vue';
import Post from '../components/Post.vue';
import PostListPerCategory from '../components/PostListPerCategory.vue';

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
    path: '/categories/:category_slug/:category_id/posts',
    component: PostListPerCategory,
    name: 'post.show',
    props: true
  },
  {
    path: '/tags/:tag_slug/posts',
    component: PostListPerTag,
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