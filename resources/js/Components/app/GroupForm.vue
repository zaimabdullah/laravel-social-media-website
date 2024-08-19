<script setup>
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  import TextInput from '../TextInput.vue';
  import Checkbox from '../Checkbox.vue';

  defineProps({
    form: Object
  });

  const editor = ClassicEditor;
  const editorConfig = {
    toolbar: ['heading', '|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote']
  };


</script>

<template>
  <div class="mb-3 dark:text-gray-100">
    <label>Group Name</label>
    <TextInput type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
  </div>

  <div class="mb-3 dark:text-gray-100">
    <label>
      <Checkbox name="autoapproval" v-model:checked="form.auto_approval" />
      Enable Auto Approval
    </label>
  </div>

  <div class="mb-3 dark:text-gray-100">
    <label>About Group</label>
    <ckeditor :editor="editor" v-model="form.about" :config="editorConfig"></ckeditor>
  </div>
</template>

<style scoped></style>
