<script setup>
import { ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import FollowingItem from "@/Components/app/FollowingItem.vue";

const searchKeyword = ref("");
</script>

<template>
  <TextInput v-model="searchKeyword" placeholder="Type to search" class="w-full mt-4" />
  <div class="mt-3 h-[200px] lg:flex-1 overflow-auto">
    <div v-if="false" class="text-gray-400 text-center p-3">
      You don't have friends yet.
    </div>
    <div v-else>
      <FollowingItem image="https://picsum.photos/100" title="John Doe"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="Elon Musk"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="John Doe"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="Elon Musk"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="John Doe"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="Elon Musk"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="John Doe"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="Elon Musk"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="John Doe"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="Elon Musk"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="John Doe"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="Elon Musk"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="John Doe"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="Elon Musk"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="John Doe"
        description="Lorem Ipsum is simply dummy text of the printing." />
      <FollowingItem image="https://picsum.photos/100" title="Elon Musk"
        description="Lorem Ipsum is simply dummy text of the printing." />
    </div>
  </div>
</template>