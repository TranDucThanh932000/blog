<template>
  <div class="mt-5">
    <v-row align="center" class="ma-0">
        <p style="font-size: 26px">Thể loại: {{ $store.state.category ? $store.state.category.name : ''}}</p>
    </v-row>
    <v-row align="center" justify="center">
      <div>
        <v-card class="mx-auto" max-width="400" elevation="8" v-if="mainPost.id" @click="$router.push({ name: 'detail-blog', params: {id: mainPost.id} })">
          <v-img
            class="white--text align-end"
            height="200px"
            :src="mainPost.image"
          >
            <v-card-title class="text--primary">{{ mainPost.title }}</v-card-title>
          </v-img>
          <v-card-subtitle class="pb-0">
            {{ mainPost.author ? mainPost.author.name : "" }}
          </v-card-subtitle>
          <v-card-text class="text--primary">
            <div>{{ new Date(mainPost.created_at).toLocaleString() }}</div>
            <div>{{ mainPost.short_description }}</div>
          </v-card-text>
        </v-card>
        <div v-else><span style="font-size: 40px">Không có bài viết nào</span></div>
      </div>
    </v-row>
    <v-row>
      <v-col
        md="4"
        v-for="(blog, index) in remainingPost"
        :key="index"
      >
        <v-card
          class="mx-auto"
          color="rgb(64 50 67)"
          dark
          max-width="400"
          min-height="300px"
          elevation="4"
          @click="$router.push({ name: 'detail-blog', params: {id: blog.id} })"
        >
          <v-card-title>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">{{
                  formatTextTooltip(blog.title, 50)
                }}</span>
              </template>
              <span class="text-h6 font-weight-light">{{ blog.title }}</span>
            </v-tooltip>
          </v-card-title>

          <v-card-text class="text-h5 font-weight-bold pb-0">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">{{
                  formatTextTooltip(blog.short_description, 90)
                }}</span>
              </template>
              <span>{{ blog.short_description }}</span>
            </v-tooltip>
          </v-card-text>

          <v-card-actions class="py-0" style="margin-bottom: -5px">
            <v-list-item class="px-2">
              <v-list-item-content>
                <v-list-item-title>{{ new Date(blog.created_at).toLocaleString() }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-card-actions>

          <v-card-actions class="py-0">
            <v-list-item class="grow px-2" >
              <v-list-item-avatar color="grey darken-3">
                <v-img
                  class="elevation-6"
                  alt=""
                  :src="blog.author.avatar"
                ></v-img>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>{{ blog.author.name }}</v-list-item-title>
              </v-list-item-content>

            </v-list-item>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    <v-row align="center" justify="center">
      <div class="py-2" v-if="mainPost.id">
        <v-pagination
          v-model="page"
          :length="pageCount"
          :total-visible="8"
        ></v-pagination>
      </div>
    </v-row>
    <toast-message ref="toastMessage" />
    <Loading ref="loading"> </Loading>
  </div>
</template>

<script>
import AppService from "@/services/app.service";
import ToastMessage from "@/components/ToastMessage";
import Loading from "@/components/Loading";

export default {
  name: 'SearchHomePageByCategory',
  components: {
    ToastMessage,
    Loading,
  },
  data() {
    return {
      mainPost: {},
      remainingPost: [],
      pageCount: 1,
      page: 1,
    };
  },
  async mounted() {
    this.$refs["loading"].open();
    await Promise.all([this.$store.dispatch('getCurrentUser') ,this.getMenuById(), this.getBlogBySearch(1)])
    this.$refs["loading"].close();
  },
  methods: {
    getBlogBySearch(page) {
        this.$store.dispatch('updateUser', )
      return AppService.getBlogByCategorySearch(this.$route.query.txtSearch, this.$route.query.category, page)
        .then((res) => {
          this.mainPost = {};
          this.remainingPost = [];
          if (res.status === 200) {
            if (res.data.blogs.length !== 0) {
              this.mainPost = res.data.blogs[0];
              if (res.data.blogs.length > 1) {
                for (let i = 1; i < res.data.blogs.length; i++) {
                  this.remainingPost.push(res.data.blogs[i]);
                }
              }else{
                this.remainingPost = [];
              }
              this.pageCount = res.data.pageCount;
            } else {
              this.mainPost = {};
              this.remainingPost = [];
              this.pageCount = 0;
            }
          }
        })
        .catch((res) => {
          this.mainPost = {};
          this.remainingPost = [];
          this.pageCount = 0;
          this.$refs["toastMessage"].open(res.response.data, true);
        });
    },
    formatTextTooltip(item, max) {
      if (item.length > max) {
        return item.substring(0, max) + "...";
      }
      if (item.includes("null")) {
        return "";
      }
      return item;
    },
    getMenuById(){
        return AppService.getMenuById(this.$route.query.category)
        .then(res => {
            if(res.status === 200){
                this.$store.dispatch('updateCategory', res.data)
            }else{
                this.$emit('toastMessage', res.data)
                this.$store.dispatch('updateCategory', null, true)
            }
        })
        .catch(res => {
            this.$emit('toastMessage', res.response.data, true)
            this.$store.dispatch('updateCategory', null)
        })
    }
  },
  watch: {
    async page() {
      this.$refs["loading"].open();
      await this.getBlogBySearch(this.page);
      this.$refs["loading"].close();
    },
    async '$route.query.txtSearch'(){
      this.$refs["loading"].open();
      this.page = 1
      await Promise.all([this.$store.dispatch('getCurrentUser'), this.getBlogBySearch(this.page)])
      this.$refs["loading"].close();
    },
    async '$route.query.category'(){
      this.$refs["loading"].open();
      this.page = 1
      await Promise.all([ this.$store.dispatch('getCurrentUser'), this.getMenuById(), this.getBlogBySearch(this.page)]) 
      this.$refs["loading"].close();
    },
  },
  beforeDestroy() {
    this.$store.dispatch('updateCategory', null)
  },
};
</script>