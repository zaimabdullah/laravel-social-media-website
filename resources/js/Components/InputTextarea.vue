<script setup>
  import { onMounted, ref, watch } from 'vue';

  const model = defineModel({
    type: String,
    required: false,
  });

  const input = ref(null);


  defineExpose({ focus: () => input.value.focus() });

  const props = defineProps({
    placeholder: String,
    autoResize: {
      type: Boolean,
      default: true
    }
  });

  function adjustHeight() {
    if (props.autoResize) {
      input.value.style.height = 'auto';
      input.value.style.height = (input.value.scrollHeight + 1) + 'px';
    }
  }

  onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
      input.value.focus();
    }
    adjustHeight();
  });

  watch(model, () => {
    setTimeout(() => {
      adjustHeight();
    }, 0);
  });
</script>

<template>
  <textarea
    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
    v-model="model" @input="adjustHeight" ref="input" :placeholder="placeholder"></textarea>
</template>
