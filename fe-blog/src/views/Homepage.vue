<template>
  <div style="width: 100%">
      <h1>{{ mainPost.title }}</h1>
      <v-row class="ma-0">
        <span class="mr-5">Tác giả: <span class="text-decoration-underline" style="cursor: pointer;color:#00b9ad" @click="showProfile(mainPost.author.id)">{{ mainPost.author ? mainPost.author.name: '' }}</span></span>
        <span>Kiểm tra bởi: <span class="text-decoration-underline" style="cursor: pointer;color:#00b9ad" @click="showProfile(mainPost.censor.id)">{{ mainPost.censor ? mainPost.censor.name : '' }}</span></span>
      </v-row>
      <v-row class="ma-0" align="center">
        <v-btn @click="chooseStar(index)" icon v-for="index in 5" :key="index">
          <v-icon :class="'star' + index">mdi-star-outline</v-icon>
        </v-btn>
        <span class="ml-2">{{ mainPost.ratedStar }}/5 - ({{ mainPost.totalRated }} bình chọn)</span>
      </v-row>
      <v-row class="ma-0 my-3">
        <span>Ngày đăng: {{ mainPost.date_publish }}</span>
      </v-row>
      <v-row class="ma-0">
        <div v-html="mainPost.content"></div>
      </v-row>
  </div>
</template>

<script>
import AppService from "@/services/app.service";

export default {
  data() {
    return{
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
    }
  },
  created(){
    this.getMainBlog()
  },
  methods: {
    showProfile(id){
      console.log(id)
    },
    chooseStar(index){
      console.log(index)
    },
    getMainBlog(){
      return AppService.getMainBlog(1)
      .then((res) => {
        if(res.status === 200){
          this.mainPost = res.data
        }else{
          this.$emit('toastMessage', res.data, true)
        }
      })
      .catch(res => {
        this.$emit('toastMessage', res.data, true)
      })
    }
  }
}
</script>
<style scoped>
#myeditor{
  background-color: white;
}
</style>