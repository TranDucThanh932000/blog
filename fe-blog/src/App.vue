<template>
  <v-app>
    <!-- <v-navigation-drawer app>
    </v-navigation-drawer> -->

    <v-app-bar app color="primary lighten-1">
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

export default {
  name: 'App',
  components: {
    Loading,
    ToastMessage,
    MenuHeader
  },
  data() {
    return{
      menuHeader: [
        {
          name: 'AAAAA',
          id: 1,
          children: [
            {
              name: 'AAAAA1',
              id: 2,
              children: []
            },
            {
              name: 'AAAAA2',
              id: 2,
              children: []
            }
          ]
        },
        {
          name: 'AAAAA',
          id: 2,
          children: [
            {
              name: 'AAAAA1',
              id: 2,
              children: []
            },
            {
              name: 'AAAAA2',
              id: 2,
              children: []
            }
          ]
        },
        {
          name: 'AAAAA3',
          id: 3,
          children: [
            {
              name: 'AAAAA31',
              id: 2,
              children: []
            },
            {
              name: 'AAAAA32',
              id: 10,
              children: [
                  {
                    name: 'AAAAA3331',
                    id: 11,
                    children: []
                  },
                  {
                    name: 'AAAAA3332',
                    id: 12,
                    children: []
                  },
              ]
            }
          ]
        },
        {
          name: 'AAAAA4',
          id: 4,
          children: []
        },
        {
          name: 'AAAAA',
          id: 5,
          children: [
            {
              name: 'AAAAA1',
              id: 2,
              children: []
            },
            {
              name: 'AAAAA2',
              id: 2,
              children: []
            }
          ]
        },
      ]
    }
  }
};
</script>
