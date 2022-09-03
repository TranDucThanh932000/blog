<template>
  <v-app>
    <!-- <v-navigation-drawer app>
    </v-navigation-drawer> -->

    <v-app-bar app color="primary lighten-1">
      <div>
        <v-img class="logo-web" sizes="0" src="https://cocmusic.herokuapp.com/img/logo-mountain.43026b47.png"></v-img>
      </div>
      <v-spacer></v-spacer>
      <template v-for="menu in menuHeader">
        <MenuHeader :items="menu.children" :key="menu.id" :name="menu.name" :offsetx="false" :offsety="true"></MenuHeader>
      </template>
    </v-app-bar>

    <v-main>
      <v-container fluid style="height: 100%">
        <v-row>
          <v-col md="8" offset-md="1">
            <router-view style="height: 100%"></router-view>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

    <v-footer app>
    </v-footer>
    <Loading ref="loading"> </Loading>
    <toast-message ref="toastMessage" />
  </v-app>
</template>

<script>
import Loading from "@/components/Loading";
import ToastMessage from "@/components/ToastMessage";
import MenuHeader from "@/components/MenuHeader.vue"
import AppService from "@/services/app.service";

export default {
  name: 'App',
  components: {
    Loading,
    ToastMessage,
    MenuHeader
  },
  async created(){
    await this.getMenu()
  },
  data() {
    return{
      menuHeader: []
    }
  },
  methods:{
    getMenu(){
      return AppService.getMenu()
      .then((res) => {
        this.menuHeader = res.data
      })
      .catch((res) => {
        this.$refs['toastMessage'].open(res.message, true)
      })
    }
  }
};
</script>

<style>
.logo-web .v-image__image--cover{
  background-size: auto !important;
}
</style>
