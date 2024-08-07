<script setup>
  import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
  import { HandThumbUpIcon, ChatBubbleLeftRightIcon } from '@heroicons/vue/24/outline';
  import PostUserHeader from '@/Components/app/PostUserHeader.vue';
  import { router } from '@inertiajs/vue3';
  import axiosClient from '@/axiosClient.js';
  import ReadMoreReadLess from './ReadMoreReadLess.vue';
  import EditDeleteDropdown from './EditDeleteDropdown.vue';
  import PostAttachments from './PostAttachments.vue';
  import CommentList from './CommentList.vue';

  const props = defineProps({
    post: Object
  });

  const emit = defineEmits(['editClick', 'attachmentClick']);

  function openEditModal() {
    emit('editClick', props.post);
  }

  function deletePost() {
    if (window.confirm('Are you sure you want to delete this post?')) {
      router.delete(route('post.destroy', props.post), {
        preserveScroll: true
      });
    }
  }

  function openAttachment(ind) {
    emit('attachmentClick', props.post, ind);
  }

  function sendReaction() {
    axiosClient.post(route('post.reaction', props.post), {
      reaction: 'like'
    }).then(({ data }) => {
      props.post.current_user_has_reaction = data.current_user_has_reaction;
      props.post.num_of_reactions = data.num_of_reactions;
    });
  }

</script>

<template>
  <div class="bg-white border rounded p-4 mb-3">
    <div class="flex items-center justify-between mb-3">

      <PostUserHeader :post="post" />

      <EditDeleteDropdown :user="post.user" @edit="openEditModal" @delete="deletePost" />
    </div>
    <div class="mb-3">
      <!-- ReadMoreReadLess for post content -->
      <ReadMoreReadLess :content="post.body" />
    </div>
    <!-- Attachments of post content -->
    <div class="grid gap-3 mb-3" :class="[
      post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'
    ]">
      <PostAttachments :attachments="post.attachments" @attachmentClick="openAttachment" />
    </div>
    <!-- Like & Comment -->
    <Disclosure v-slot="{ open }">
      <!-- Like & Comment buttons -->
      <div class="flex gap-2">
        <button @click="sendReaction"
          class="flex text-gray-800 gap-1 items-center justify-center py-2 px-4 rounded-lg flex-1" :class="[
            post.current_user_has_reaction ? 'bg-sky-100 hover:bg-sky-200' : 'bg-gray-100 hover:bg-gray-200'
          ]">
          <HandThumbUpIcon class="h-5 w-5" />
          <span class="mr-2">{{ post.num_of_reactions }}</span>
          {{ post.current_user_has_reaction ? 'Unlike' : 'Like' }}
        </button>

        <!-- Comments Section -->
        <DisclosureButton
          class="flex text-gray-800 gap-1 items-center justify-center py-2 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 flex-1">
          <ChatBubbleLeftRightIcon class="h-5 w-5" />
          <span class="mr-2">{{ post.num_of_comments }}</span>
          Comment
        </DisclosureButton>
      </div>

      <DisclosurePanel class="scrollbar-thin mt-3 max-h-[400px] overflow-auto">
        <CommentList :post="post" :data="{ comments: post.comments }" />
      </DisclosurePanel>
    </Disclosure>
    <!--/ End Comments Section -->
  </div>
  <!--/ End Like & Comment -->
</template>
