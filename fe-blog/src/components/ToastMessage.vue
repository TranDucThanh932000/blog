<template>
  <div class="ToastMessage" 
  >
    <v-snackbar
      class="mt-15"
      v-model="showError"
      timeout="2000"
      right
      top
      color="red lighten-1" 
      elevation="24"
    >
      <b> {{ message }} </b>
    </v-snackbar>

    <v-snackbar
      class="mt-15"
      v-model="showSuccess"
      timeout="2000"
      right
      top
      color="success lighten-1"
      elevation="24"
    >
      {{ message }}
    </v-snackbar>
  </div>
</template>

<script>
export default {
  name: "ToastMessage",
  data() {
    return {
      message: "",
      showError: false,
      showSuccess: false,
    };
  },
  methods: {
    open(message, isError) {
      if (isError) {
        this.showError = true;
      } else {
        this.showSuccess = true;
      }
      this.message = message;
    },
  },
};
</script>

<style scoped>
</style>