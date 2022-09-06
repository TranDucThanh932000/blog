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
      <div>
        <v-img
          class="logo-web"
          sizes="0"
          src="https://cocmusic.herokuapp.com/img/logo-mountain.43026b47.png"
        ></v-img>
      </div>
      <v-spacer></v-spacer>
      <div v-if="showMenu" style="display: flex">
        <template v-for="menu in menuHeader">
          <MenuHeader
            :items="menu.children"
            :key="menu.id"
            :name="menu.name"
            :offsetx="false"
            :offsety="true"
          ></MenuHeader>
        </template>
      </div>
      <v-spacer></v-spacer>
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
    </v-app-bar>

    <v-main>
      <v-container fluid style="height: 100%">
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
              123 test 123123 123 test 123123 123 test 123123 123 test 123123
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
    await Promise.all([this.getMenu(), this.getSideBarAdmin()]);
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
      listNotShowTotalMenu: ["login", "admin"],
      showSidebar: false,
      showSidebarRight: true,
      showMenu: true,
      totalMenu: true,
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
          } else {
            this.sidebar = [];
            this.toastMessage(res.data, true);
          }
        })
        .catch((res) => {
          this.sidebar = [];
          this.toastMessage(res.data, true);
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
  },
  watch: {
    "$route.name"() {
      this.showComponentOfApp();
    },
  },
};
</script>

<style>
.logo-web .v-image__image--cover {
  background-size: auto !important;
}
</style>
