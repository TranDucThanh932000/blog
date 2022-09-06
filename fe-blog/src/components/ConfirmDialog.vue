<template>
  <v-dialog v-model="show" max-width="900">
    <v-card>
      <v-card-title
        style="background-color: rgb(26 118 207 / 1); color: white"
        class="justify-center py-2"
      >
        {{ txtTitle }}
      </v-card-title>
      <v-card-title class="justify-center" style="opacity: 0.6">
        {{ question }}
      </v-card-title>
      <v-card-actions class="justify-center pb-5">
        <v-btn
          class="mr-2"
          elevation="2"
          color="primary darken-1"
          text
          @click="confirm()"
        >
          Xác nhận
        </v-btn>
        <v-btn
          class="ml-2"
          elevation="2"
          color="warning darken-1"
          text
          @click="cancel"
        >
          Hủy bỏ
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "ConfirmDialog",
  props: ["txtTitle", "question"],
  data() {
    return {
      show: false,
      item: null,
      promise: null,
    };
  },
  methods: {
    open(item) {
      this.show = true;
      this.item = item;
      return new Promise((resolve, reject) => {
        this.promise = {
          resolve,
          reject,
        };
      });
    },
    confirm() {
      this.promise.resolve(this.item);
      this.show = false;
    },
    cancel() {
      this.promise.reject();
      this.show = false;
    },
  },
};
</script>

<style scoped>
</style>