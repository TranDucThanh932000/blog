<template>
  <div>
        <ckeditor     
        v-model="editorText"
        ref="ckeditor"/>
        <button @click="save">Save</button>
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
    async save() {
      console.log(this.editorText)
    },
  },
};
</script>