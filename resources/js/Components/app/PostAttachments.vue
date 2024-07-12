<script setup>
  import { PaperClipIcon } from '@heroicons/vue/20/solid';
  import { isImage } from '@/helpers';
  import { ArrowDownTrayIcon } from '@heroicons/vue/24/outline';

  defineProps({
    attachments: Array
  });

  defineEmits(['attachmentClick']);

</script>

<template>
  <!-- <pre>{{ attachments }}</pre> -->
  <template v-for="(attachment, ind) of attachments.slice(0, 4)">

    <div @click="$emit('attachmentClick', ind)"
      class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative cursor-pointer">

      <div v-if="ind === 3 && attachments.length > 4"
        class="absolute left-0 top-0 right-0 bottom-0 z-10 bg-black/60 text-white flex items-center justify-center text-xl">
        +{{ attachments.length - 4 }} more
      </div>

      <!-- Download -->
      <a @click.stop :href="route('post.download', attachment)"
        class="z-10 opacity-0 group-hover:opacity-100 transition-all w-8 h-8 flex items-center justify-center text-gray-100 bg-gray-700 rounded absolute right-2 top-2 cursor-pointer hover:bg-gray-800">
        <ArrowDownTrayIcon class="w-4 h-4" />
      </a>
      <!--/ Download -->

      <!-- Image File -->
      <img v-if="isImage(attachment)" :src="attachment.url" class="object-contain aspect-square" />
      <!-- Not Image File -->
      <div v-else class="flex flex-col justify-center items-center">
        <PaperClipIcon class="w-10 h-10 mb-3" />
        <small>{{ attachment.name }}</small>
      </div>
      <!--/ Not Image File -->
    </div>
  </template>
</template>
