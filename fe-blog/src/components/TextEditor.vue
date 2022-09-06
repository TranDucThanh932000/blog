<template>
  <div>
        <ckeditor     
        v-model="editorText"
        ref="ckeditor"/>
        <v-row align="center" justify="center" class="ma-0 mt-5">
          <v-btn @click="save" color="primary">Lưu bài viết</v-btn>
        </v-row>
  </div>
</template>

<script>

export default {
  components: {
  },
  data() {
    return {
      editorText: '',
    };
  },
  methods: {
    save() {
      this.$emit('save', this.editorText)
    },
  },
};
</script>