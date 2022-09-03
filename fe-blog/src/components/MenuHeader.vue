<template>
    <div>
        <v-menu close-on-content-click :offset-y="offsety" :offset-x="offsetx">
          <template v-slot:activator="{ on }">
            <v-btn v-if="items.length > 0" v-on="on" text>
                {{ name }}
                <v-spacer></v-spacer>
                <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
            <v-list-item color="green" v-on="on" v-else>
                {{ name }}
            </v-list-item>
          </template>
          <v-list v-if="items.length > 0">
            <div v-for="(item, index) in items" :key='index'>
              <MenuHeader
                v-if="item.children.length > 0"
                :items="item.children"
                :name="item.name"
                :offsety="false"
                :offsetx="true"
              ></MenuHeader>
              <v-list-item v-else>
                <v-list-item-title>{{ item.name }}</v-list-item-title>
              </v-list-item>
            </div>
          </v-list>
        </v-menu>
    </div>
</template>

<script>
export default {
  name: 'MenuHeader',
  props: ['items', 'name', 'offsetx', 'offsety']
}
</script>