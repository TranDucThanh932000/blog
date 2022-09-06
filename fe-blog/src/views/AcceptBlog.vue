<template>
  <v-card>
    <v-toolbar dense dark class="font-weight-bold" color="primary lighten-1">
      <v-toolbar-title class="text-center">Danh sách blog chưa được duyệt</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="desserts"
      :page.sync="page"
      :items-per-page="itemsPerPage"
      hide-default-footer
      class="elevation-1"
      @page-count="pageCount"
      disable-sort
      style="width: 100%"
      no-data-text="Chưa có dữ liệu"
    >
      <template v-slot:item.created_at="{ item }">
        <span>{{ new Date(item.created_at).toLocaleString() }}</span>
      </template>

      <template v-slot:item.acts="{ item }">
        <v-icon v-if="isShow" small class="mr-2" @click="showItem(item.id)">
          mdi-eye
        </v-icon>
        <v-icon v-if="isDelete" small class="ml-2" @click="deleteItem(item.id)">
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>
    <div class="pt-2">
      <v-pagination
        v-model="page"
        :length="pageCount"
        :total-visible="8"
      ></v-pagination>
    </div>
    <Loading ref="loading" />
    <ConfirmDialog ref="confirmDialog" txtTitle="Xóa bài viết" question="Bạn đã chắc chắn muốn xóa bài viết này chưa?"/>
    <toast-message ref="toastMessage" />
  </v-card>
</template>

<script>
import AppService from "@/services/app.service";
import Loading from "@/components/Loading.vue";
import ConfirmDialog from "@/components/ConfirmDialog.vue";
import ToastMessage from "@/components/ToastMessage";

export default {
  components: {
    Loading,
    ConfirmDialog,
    ToastMessage
  },
  data() {
    return {
      page: 1,
      pageCount: 1,
      itemsPerPage: 10,
      headers: [
        {
          text: "STT",
          value: "stt",
        },
        { text: "Title", value: "title" },
        { text: "Tác giả", value: "author_id_name" },
        { text: "Ngày đăng", value: "created_at" },
        { text: "Thao tác", value: "acts" },
      ],
      desserts: [],
      isDelete: false,
      isShow: true
    };
  },
  async mounted() {
    this.$refs["loading"].open();
    await Promise.all([this.checkDeleteAbility(), this.getBlogWithPagination(this.page)])
    this.$refs["loading"].close();
  },
  methods: {
    getBlogWithPagination(pageChoosed) {
      return AppService.getListUnaccept(pageChoosed)
        .then((res) => {
          if (res.status === 200) {
            this.desserts = res.data.blogs;
            this.desserts.forEach((x, index) => {
              x.stt = (pageChoosed - 1) * 10 + index + 1;
              x.author_id_name = x.author.name + ' - ' + x.author_id
            });
            this.pageCount = res.data.pageCount;
          } else {
            this.$emit("toastMessage", "Lỗi khi lấy danh sách blog", true);
          }
        })
        .catch((res) => {
          this.$emit("toastMessage", res.data, true);
        });
    },
    showItem(id){
      this.$router.push('/admin/preview/' + id)
    },
    deleteItem(id){
      this.$refs['confirmDialog'].open(id)
      .then((id) => {
        AppService.cancelBlog(id)
        .then(async (res) => {
            if(res.status === 200){
                this.$refs['toastMessage'].open(res.data, false)
                this.$refs["loading"].open();
                await this.getBlogWithPagination(this.page)
                this.$refs["loading"].close();
            }else{
                this.$refs['toastMessage'].open(res.data, true)
            }
        })
        .catch(res => {
            this.$refs['toastMessage'].open(res.response.data, true)
        })
      })
      .catch(() => {})
    },
    checkDeleteAbility(){
      return AppService.checkAbility('delete_blog')
      .then((res) => {
        if(res.status === 200){
          this.isDelete = res.data
        }else{
          this.isDelete = false
          this.$emit("toastMessage", "Không có quyền xóa bài viết", true);
        }
      })
      .catch(res => {
        this.isDelete = false
        this.$emit("toastMessage", res.data, true);
      })
    }
  },
  watch: {
    async page() {
      this.$refs["loading"].open();
      await this.getBlogWithPagination(this.page);
      this.$refs["loading"].close();
    },
  },
};
</script>