<template>
  <v-app>
    <v-navigation-drawer app v-if="showSidebar">
      <v-treeview activatable :items="sidebar" open-on-click transition>
        <template class="ml-0 pl-0" v-slot:label="{ item }">
          <v-list-item
            class="pl-0 ml-0"
            v-if="item.children.length > 0"
            link
            :key="item.name_path"
          >
            <v-list-item-title class="font-weight-bold">
              {{ item.name }}
            </v-list-item-title>
          </v-list-item>
          <v-list-item
            class="pl-0 ml-0"
            v-else
            link
            :key="item.name_path"
            @click="$route.name === item.name_path ? '' : $router.push({ name: item.name_path })"
          >
            <v-list-item-title class="font-weight-bold">
              {{ item.name }}
            </v-list-item-title>
          </v-list-item>
        </template>
      </v-treeview>
    </v-navigation-drawer>

    <v-app-bar v-if="totalMenu" app color="primary lighten-1">
      <v-row align="center">
        <v-col sm="12" md="3" style="text-align: start;">
          <div @click="clickLogo" style="cursor: pointer;">
            <v-img
              class="logo-web"
              src="https://cocmusic.herokuapp.com/img/logo-mountain.43026b47.png"
            ></v-img>
          </div>
        </v-col>
        <v-col sm="12" md="6">
          <div v-if="showMenu" style="display: flex;justify-content: center;">
            <template v-for="menu in menuHeader">
              <MenuHeader
                @resetTxtSearch="resetTxtSearch"
                :items="menu.children"
                :key="menu.id"
                :name="menu.name"
                :id="menu.id"
                :offsetx="false"
                :offsety="true"
              ></MenuHeader>
            </template>
          </div>
        </v-col>
        <v-col sm="12" md="3" style="text-align: end;">
          <v-menu v-if="isLogin()" offset-y>
            <template v-slot:activator="{ on }">
              <v-btn v-on="on" icon color="white">
                <v-icon>mdi-account-arrow-down</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-account-circle-outline</v-icon>
                </v-list-item-icon>
                <v-list-item-content>Tài khoản</v-list-item-content>
              </v-list-item>
              <v-list-item @click="logout">
                <v-list-item-icon>
                  <v-icon>mdi-logout-variant</v-icon>
                </v-list-item-icon>
                <v-list-item-content>Đăng xuất</v-list-item-content>
              </v-list-item>
            </v-list>
          </v-menu>
          <v-btn v-else icon color="white" @click="$router.push({ name: 'login' })">
            <v-icon>mdi-account</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-app-bar>

    <v-main>
      <v-container fluid style="height: 100%;" class="pa-0">
        <v-row style="width: 100%" class="ma-0 pt-3" v-if="showSearchMain">
          <v-col md="12" class="pa-0">
            <div class="setBg">
              <v-row justify="center">
                <p class="mt-3" style="color: white; font-size: 26px">
                  Chào mừng bạn đến với COC MOUNTAIN
                </p>
              </v-row>
              <v-row justify="center">
                <p style="color: white">Nơi chia sẻ mọi thứ trên đời</p>
              </v-row>
              <v-row justify="center">
                <v-col md="6">
                    <v-text-field
                      shaped
                      outlined
                      v-model="txtSearch"
                      placeholder="Bạn muốn tìm hiểu về gì?"
                      color="white"
                      class="text-white"
                      append-icon="mdi-magnify"
                      @click:append="search"
                      @keyup.enter="search"
                    ></v-text-field>
                </v-col>
              </v-row>
            </div>
          </v-col>
        </v-row>
        <v-row style="width: 100%">
          <v-col
            :md="showSidebar || !showSidebarRight || $route.name == 'login' ? 12 : 8"
            :offset-md="showSidebar || $route.name == 'login' ? 0 : 1"
          >
            <router-view
              @toastMessage="toastMessage"
              style="height: 100%"
            ></router-view>
          </v-col>
          <v-col v-if="showSidebarRight" md="2">
            <p>
              Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo Chỗ hiển thị quảng cáo
            </p>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

    <v-footer app> </v-footer>
    <Loading ref="loading"> </Loading>
    <toast-message ref="toastMessage" />
  </v-app>
</template>

<script>
import Loading from "@/components/Loading";
import ToastMessage from "@/components/ToastMessage";
import MenuHeader from "@/components/MenuHeader.vue";
import AppService from "@/services/app.service";

export default {
  name: "App",
  components: {
    Loading,
    ToastMessage,
    MenuHeader,
  },
  async created() {
    if(localStorage.getItem('myblog_token')){
      await Promise.all([this.getMenu(), this.getSideBarAdmin()]);
    }else{
      await Promise.all([this.getMenu()]);
    }
  },
  data() {
    return {
      menuHeader: [],
      sidebar: [],
      listShowSidebarAdmin: [
        "admin",
        "list-blog",
        "accept-blog",
        "preview"
      ],
      listNotShowSidebarRight: [
        "login",
        "admin",
        "list-blog",
        "accept-blog",
        "preview"
      ],
      listNotShowMenu: [
        "login",
        "admin",
        "list-blog",
        "accept-blog",
        "create-blog",
        "update-blog",
        "preview"
      ],
      listShowSearchMain: [
        'homepage',
        'search',
        'search-category'
      ],
      listNotShowTotalMenu: ["login", "admin"],
      showSidebar: false,
      showSidebarRight: true,
      showMenu: true,
      totalMenu: true,
      showSearchMain: true,
      txtSearch: ''
    };
  },
  methods: {
    getMenu() {
      return AppService.getMenu()
        .then((res) => {
          if (res.status === 200) {
            this.menuHeader = res.data;
          } else {
            this.menuHeader = [];
            this.toastMessage(res.data, true);
          }
        })
        .catch((res) => {
          this.$refs["toastMessage"].open(res.data, true);
        });
    },
    toastMessage(mess, flag) {
      this.$refs["toastMessage"].open(mess, flag);
    },
    getSideBarAdmin() {
      return AppService.getSideBarAdmin()
        .then((res) => {
          if (res.status === 200) {
            this.sidebar = res.data;
            for(let i = 0;i < this.sidebar.length; i++){
              if(this.sidebar[i].children.length > 0){
                for(let j = 0; j < this.sidebar[i].children.length; j++){
                  if(!this.$store.state.user.roles.includes(this.sidebar[i].children[j].name_path.replaceAll('-', '_'))){
                    this.sidebar[i].children.splice(j, 1)
                    j--
                  }
                }
              }
              if(this.sidebar[i].children.length === 0){
                this.sidebar.splice(i, 1)
                i--
              }
            }
          } else {
            this.sidebar = [];
            this.toastMessage(res.data, true);
          }
        })
        .catch((res) => {
          this.sidebar = [];
          this.toastMessage(res.response, true);
        });
    },
    showComponentOfApp() {
      if (this.listShowSidebarAdmin.includes(this.$route.name)) {
        this.showSidebar = true;
      } else {
        this.showSidebar = false;
      }
      if (this.listNotShowSidebarRight.includes(this.$route.name)) {
        this.showSidebarRight = false;
      } else {
        this.showSidebarRight = true;
      }
      if (this.listNotShowMenu.includes(this.$route.name)) {
        this.showMenu = false;
      } else {
        this.showMenu = true;
      }
      if (this.listNotShowTotalMenu.includes(this.$route.name)) {
        this.totalMenu = false;
      } else {
        this.totalMenu = true;
      }
      if(this.listShowSearchMain.includes(this.$route.name)){
        this.showSearchMain = true
      }else{
        this.showSearchMain = false
      }
    },
    logout() {
      return AppService.logout()
        .then((res) => {
          if (res.status === 200) {
            localStorage.removeItem("myblog_token");
            this.$store.dispatch('updateUser', null);
            this.$router.push({ name: "login" });
          } else {
            this.toastMessage("Đăng xuất thất bại", true);
          }
        })
        .catch((res) => {
          this.toastMessage(res.response.data.message, true);
        });
    },
    isLogin() {
      if (this.$store.state.user) {
        return true;
      }
      return false;
    },
    search(){
      if(this.txtSearch.length > 0 && this.txtSearch.length <= 50){
        if(this.$route.name === 'homepage' || this.$route.name === 'search'){
          this.$router.push({ name: 'search', params: { txtSearch: this.txtSearch }})
        }else{
          this.$router.push({ name: 'search-category', query: { category: this.$store.state.category.id, txtSearch: this.txtSearch }})
        }
      }
    },
    resetTxtSearch(){
      this.txtSearch = ''
    },
    clickLogo(){
      this.txtSearch = ''
      this.$router.push({ name: 'homepage' })
    }
  },
  watch: {
    "$route.name"() {
      this.showComponentOfApp();
    },
  }
};
</script>

<style>
.logo-web .v-image__image--cover {
  background-size: auto !important;
  background-position: center left !important;
  margin-left: -45px;
}
.setBg {
  background-image: url("./assets/bg.jpg");
  background-size: 100%;
  background-color: black;
}
.text-white input {
  color: white !important;
}

.text-white input::placeholder {
  color: white !important;
}

.text-white .v-input {
  color: white !important;
}

.text-white fieldset {
  border-color: white;
}

.text-white .v-input__icon--append .v-icon { 
    color: white;
}
</style>
