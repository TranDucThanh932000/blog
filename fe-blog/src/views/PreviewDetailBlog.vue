<template>
  <div style="width: 100%">
      <h1>{{ mainPost.title }}</h1>
      <v-row class="ma-0">
        <span class="mr-5">Tác giả: <span class="text-decoration-underline" style="cursor: pointer;color:#00b9ad" @click="showProfile(mainPost.author.id)">{{ mainPost.author ? mainPost.author.name : '' }}</span></span>
      </v-row>
      <v-row class="ma-0 my-3">
        <span>Ngày đăng: {{ mainPost.date_publish }}</span>
      </v-row>
      <v-row class="ma-0">
        <div v-html="mainPost.content"></div>
      </v-row>
      <v-row class="ma-0" align="center" justify="center">
        <v-btn @click="accept" color="primary" class="mr-1" v-if="!mainPost.censor_id">Duyệt bài viết</v-btn>
        <v-btn @click="cancel" color="warning" class="ml-2">Hủy bài viết</v-btn>
      </v-row>
      <ToastMessage ref="toastMessage"/>
  </div>
</template>

<script>
import AppService from "@/services/app.service";
import ToastMessage from "@/components/ToastMessage";

export default {
    components:{
        ToastMessage
    },
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
  mounted(){
    this.getBlogPreview()
  },
  methods: {
    showProfile(id){
      console.log(id)
    },
    getBlogPreview(){
      return AppService.getBlogPreview(this.$route.params.id)
      .then((res) => {
        if(res.status === 200){
          this.mainPost = res.data
        }else{
          this.mainPost = {}
          this.$refs["toastMessage"].open(res.data, true);
          this.$router.push({name: 'homepage'})
        }
      })
      .catch((res) => {
        this.mainPost = {}
        this.$refs["toastMessage"].open(res.response.data + ', trở về trang chủ', true);
        this.$router.push({name: 'homepage'})
      })
    },
    accept(){
        AppService.acceptBlog(this.$route.params.id)
        .then((res) => {
            if(res.status === 200){
                this.$refs['toastMessage'].open(res.data, false)
                this.$router.push({ name: 'list-blog'})
            }else{
                this.$refs['toastMessage'].open(res.data, true)
            }
        })
        .catch(res => {
            this.$refs['toastMessage'].open(res.response.data, true)
        })
    },
    cancel(){
        AppService.cancelBlog(this.$route.params.id)
        .then((res) => {
            if(res.status === 200){
                this.$refs['toastMessage'].open(res.data, false)
                this.$router.push({ name: 'list-blog'})
            }else{
                this.$refs['toastMessage'].open(res.data, true)
            }
        })
        .catch(res => {
            this.$refs['toastMessage'].open(res.response.data, true)
        })
    }
  },
  watch:{
    '$route.params.id'(){
        this.getBlogPreview()
    }
  }
}
</script>
<style scoped>
#myeditor{
  background-color: white;
}
</style>