<script setup>
  import { usePage } from '@inertiajs/vue3';
  import { onMounted, onUpdated, ref, watch } from 'vue';
  import axiosClient from '@/axiosClient.js';
  import PostItem from './PostItem.vue';
  import PostModal from './PostModal.vue';
  import AttachmentPreviewModal from './AttachmentPreviewModal.vue';

  const page = usePage();

  const authUser = usePage().props.auth.user;
  const showEditModal = ref(false);
  const showAttachmentsModal = ref(false);
  const editPost = ref({});
  const previewAttachmentsPost = ref({});
  const loadMoreIntersect = ref(null);

  const allPosts = ref({
    data: [],
    next: null
  });

  // allPosts.value.data= current+old posts array
  // page.props.posts.data= newest posts array

  const props = defineProps({
    posts: Array
  });

  // substitute onUpdated(), so loadMore() work properly too
  watch(() => page.props.posts, () => {
    if (page.props.posts) {
      allPosts.value = {
        data: page.props.posts.data,
        next: page.props.posts.links?.next
      };
    }
  }, { deep: true, immediate: true });

  function openEditModal(post) {
    editPost.value = post;
    showEditModal.value = true;
  }

  function openAttachmentPreviewModal(post, index) {
    previewAttachmentsPost.value = {
      post,
      index
    };
    showAttachmentsModal.value = true;
  }

  function onModalHide() {
    editPost.value = {
      id: null,
      body: '',
      user: authUser
    };
  }

  function loadMore() {
    if (!allPosts.value.next) {
      return;
    }

    axiosClient.get(allPosts.value.next)
      .then(({ data }) => {
        // console.log(data);
        allPosts.value.data = [...allPosts.value.data, ...data.data];
        allPosts.value.next = data.links.next;
      });
  }

  // Hooks
  // onUpdated(() => {
  //   allPosts.value = {
  //     data: page.props.posts.data,
  //     next: page.props.posts.links.next
  //   };
  // });

  onMounted(() => {
    const observer = new IntersectionObserver((entries) => entries.forEach(entry => entry.isIntersecting && loadMore()), {
      rootMargin: '-250px 0px 0px 0px'
    });

    observer.observe(loadMoreIntersect.value);
  });

</script>

<template>
  <div class="overflow-auto">
    <!-- <pre>{{ allPosts.data }}</pre>
    <pre>{{ posts }}</pre> -->
    <PostItem v-for="post of allPosts.data" :key="post.id" :post="post" @editClick="openEditModal"
      @attachmentClick="openAttachmentPreviewModal" />

    <div ref="loadMoreIntersect"></div>

    <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide" />
    <AttachmentPreviewModal :attachments="previewAttachmentsPost.post?.attachments || []"
      v-model:index="previewAttachmentsPost.index" v-model="showAttachmentsModal" />
  </div>
</template>
