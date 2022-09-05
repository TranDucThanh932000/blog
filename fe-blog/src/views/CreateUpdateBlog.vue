<template>
    <div>
        <v-row class="ma-0">
            <h2>Tạo bài viết</h2>
        </v-row>
        <v-row class="ma-0">
            <v-text-field v-model="title" :rule="rules" label="Tên bài viết"></v-text-field>
        </v-row>
        <v-row class="ma-0">
          <v-autocomplete
            v-model="categories"
            :items="listCategory"
            outlined
            dense
            chips
            small-chips
            label="Thể loại"
            multiple
          ></v-autocomplete>
        </v-row>
        <v-row class="ma-0">
            <text-editor style="width: 100%" v-model="content"/>
        </v-row>
        <v-row class="ma-0" align="center" justify="center">
            <v-btn @click="save">Lưu lại</v-btn>
        </v-row>
    </div>
</template>

<script>
import TextEditor from '@/components/TextEditor'
export default {
    components: { TextEditor },
    name: 'CreateBlog',
    data() {
        return{
            rules:[
                v => !!v || 'Không được để trống'
            ],
            title: null,
            content: null,
            categories: [],
            listCategory: []
        }
    },
    methods:{
        save(){
            console.log(this.title, this.content)
        }
    }
}
</script>