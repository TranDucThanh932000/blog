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
            item-text="name"
            item-value="id"
            outlined
            dense
            chips
            small-chips
            label="Thể loại"
            multiple
          ></v-autocomplete>
        </v-row>
        <v-row class="ma-0">
            <text-editor style="width: 100%" @save="save"/>
        </v-row>
        <toast-message ref="toastMessage" />
    </div>
</template>

<script>
import TextEditor from '@/components/TextEditor'
import AppService from "@/services/app.service"
import ToastMessage from "@/components/ToastMessage";

export default {
    components: { TextEditor, ToastMessage},
    name: 'CreateBlog',
    data() {
        return{
            rules:[
                v => !!v || 'Không được để trống'
            ],
            title: null,
            categories: [],
            listCategory: []
        }
    },
    mounted(){
        this.getSubMenu();
    },
    methods:{
        save(content){
            return AppService.saveBlog({
                title: this.title,
                content: content,
                categories: this.categories
            })
            .then((res) => {
                if(res.status === 200){
                    this.$refs['toastMessage'].open(res.data, false)
                }else{
                    this.$refs['toastMessage'].open(res.data, true)
                }
            })
            .catch(res => {
                this.$refs['toastMessage'].open(res.response.data, true)
            })
        },
        getSubMenu(){
            return AppService.getSubMenu()
            .then(res => {
                if(res.status === 200){
                    this.listCategory = res.data
                }else{
                    this.listCategory = []
                    this.$refs['toastMessage'].open(res.data, true)
                }
            })
            .catch(res => {
                this.listCategory = []
                this.$refs['toastMessage'].open(res.response.data, true)
            })
        }
    }
}
</script>