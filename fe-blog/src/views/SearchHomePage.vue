<template>
  <div class="mt-5">
    <v-row align="center" justify="center">
      <div>
        <v-card class="mx-auto" max-width="400" elevation="8" v-if="mainPost.id">
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

          <v-card-text class="text-h5 font-weight-bold">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">{{
                  formatTextTooltip(blog.short_description, 90)
                }}</span>
              </template>
              <span>{{ blog.short_description }}</span>
            </v-tooltip>
          </v-card-text>

          <v-card-actions>
            <v-list-item class="grow">
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

              <v-row align="center" justify="end">
                <v-icon class="mr-1"> mdi-heart </v-icon>
                <span class="subheading mr-2">1</span>
                <span class="mr-1">·</span>
                <v-icon class="mr-1"> mdi-share-variant </v-icon>
                <span class="subheading">1</span>
              </v-row>
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
    await this.getBlogBySearch(1);
    this.$refs["loading"].close();
  },
  methods: {
    getBlogBySearch(page) {
      return AppService.getBlogBySearch(this.$route.params.txtSearch, page)
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
  },
  watch: {
    async page() {
      this.$refs["loading"].open();
      await this.getBlogBySearch(this.page);
      this.$refs["loading"].close();
    },
    async '$route.params.txtSearch'(){
      this.$refs["loading"].open();
      this.page = 1
      await this.getBlogBySearch(this.page);
      this.$refs["loading"].close();
    }
  },
};
</script>