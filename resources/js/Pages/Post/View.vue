<script setup>
  import AttachmentPreviewModal from '@/Components/app/AttachmentPreviewModal.vue';
  import PostItem from '@/Components/app/PostItem.vue';
  import PostModal from '@/Components/app/PostModal.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

  const authUser = usePage().props.auth.user;

  const showEditModal = ref(false);
  const showAttachmentsModal = ref(false);
  const editPost = ref({});
  const previewAttachmentsPost = ref({});

  defineProps({
    post: Object
  });

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

</script>

<template>
  <AuthenticatedLayout>
    <div class="p-8 w-[800px] mx-auto h-full overflow-auto">
      <PostItem :post="post" @editClick="openEditModal" @attachmentClick="openAttachmentPreviewModal" />
    </div>
    <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide" />
    <AttachmentPreviewModal :attachments="previewAttachmentsPost.post?.attachments || []"
      v-model:index="previewAttachmentsPost.index" v-model="showAttachmentsModal" />
  </AuthenticatedLayout>
</template>

<style scoped></style>
