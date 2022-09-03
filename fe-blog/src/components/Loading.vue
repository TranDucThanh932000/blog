<template>
  <div class="Loading">
    <v-overlay :value="show" z-index="1000">
      <v-progress-circular indeterminate size="64"> </v-progress-circular>
    </v-overlay>
  </div>
</template>

<script>
export default {
  name: "Loading",
  data() {
    return {
      show: false,
    };
  },
  methods: {
    open() {
      this.show = true;
    },
    close() {
      this.show = false;
    },
  },
};
</script>

<style scoped>
</style>