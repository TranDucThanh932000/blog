<template>
  <div style="width: 100%">
    <v-row justify="center">
        <p class="ma-4" style="font-size: 26px">Bài viết được ưu thích nhất</p>
    </v-row>
    <v-row>
      <v-card
        class="mx-auto mb-6"
        width="100%"
      >
        <v-img
          :src="mainPost.image"
          max-height="400px"
        ></v-img>

        <v-card-title class="py-3">
          {{ mainPost.title }}
        </v-card-title>

        <v-card-subtitle class="py-0">
          {{ mainPost.author ? mainPost.author.name : '' }}
        </v-card-subtitle>

        <v-card-subtitle class="py-0">
          {{ new Date(mainPost.created_at).toLocaleString() }}
        </v-card-subtitle>

        <v-card-actions>
          <v-btn
            color="orange lighten-2"
            text
            @click="$router.push({ name: 'detail-blog' , params: { id: mainPost.id}})"
          >
            Khám phá
          </v-btn>

          <v-spacer></v-spacer>

          <v-btn
            icon
            @click="show = !show"
          >
            <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
          </v-btn>
        </v-card-actions>

        <v-expand-transition>
          <div v-show="show">
            <v-divider></v-divider>

            <v-card-text>
              {{ mainPost.short_description }}
            </v-card-text>
          </div>
        </v-expand-transition>
      </v-card>
    </v-row>
  </div>
</template>

<script>
import AppService from "@/services/app.service";

export default {
  data() {
    return {
      mainPost: {
        // title: 'Blog là gì? Tìm hiểu về blog, blogging, blogger và cách viết blog',
        // author: {
        //   id: 1,
        //   name: 'Nguyễn Văn Anh'
        // },
        // censor: {
        //   id: 2,
        //   name: 'Trần Đức Thành'
        // },
        // ratedStar: 4.5,
        // totalRated: 5,
        // rateStar: 0,
        // datePublish: '03/09/2022'
      },
      show: false
    };
  },
  created() {
    this.getMainBlog();
  },
  methods: {
    showProfile(id) {
      console.log(id);
    },
    chooseStar(index) {
      console.log(index);
    },
    getMainBlog() {
      return AppService.getMainBlog(1)
        .then((res) => {
          if (res.status === 200) {
            this.mainPost = res.data;
          } else {
            this.$emit("toastMessage", res.data, true);
          }
        })
        .catch((res) => {
          this.$emit("toastMessage", res.data, true);
        });
    },
  },
};
</script>
<style>
#myeditor {
  background-color: white;
}
</style>