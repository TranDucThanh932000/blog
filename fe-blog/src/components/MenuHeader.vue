<template>
    <div>
        <v-menu :offset-y="offsety" :offset-x="offsetx">
          <template v-slot:activator="{ on }">
            <v-btn v-if="items.length > 0" v-on="on" text color="white">
                {{ name }}
                <v-spacer></v-spacer>
                <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
            <v-btn v-on="on" v-else text @click="choosed(item.id)" color="white">
                {{ name }}
            </v-btn>
          </template>
          <v-list class="pa-0" v-if="items.length > 0" color="primary lighten-1">
            <div v-for="(item, index) in items" :key='index'>
              <MenuHeader
                v-if="item.children.length > 0"
                :items="item.children"
                :name="item.name"
                :key="item.id"
                :offsety="false"
                :offsetx="true"
              ></MenuHeader>
              <v-btn v-else text width="100%" class="btn-left" @click="choosed(item.id)" color="white">
                <span>{{ item.name }}</span>
              </v-btn>
            </div>
          </v-list>
        </v-menu>
    </div>
</template>

<script>
export default {
  name: 'MenuHeader',
  props: ['items', 'name', 'offsetx', 'offsety'],
  methods:{
    choosed(id){
      console.log(id)
    }
  }
}
</script>

<style>
.btn-left {
  justify-content: left !important;
}
</style>