<script setup>
import PostItem from './PostItem.vue';


const post1 = {
  user: {
    id: 1,
    avatar: "https://randomuser.me/api/portraits/men/60.jpg",
    name: "John Smith",
  },
  group: null,
  attachments: [
    {
      id: 1,
      name: 'test.png',
      url: 'https://picsum.photos/1000',
      mime: 'image/png',
    },
    {
      id: 2,
      name: 'test2.png',
      url: 'https://picsum.photos/1000',
      mime: 'image/png',
    },
    {
      id: 3,
      name: 'MyDocument.docx',
      url: '#',
      mime: 'application/msword',
    },
  ],
  body: `
  <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
  
  <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
  `,
  created_at: "2024-03-30 12:04",
};
const post2 = {
  user: {
    id: 2,
    avatar: "https://randomuser.me/api/portraits/men/78.jpg",
    name: "James Does",
  },
  group: {
    id: 1,
    name: 'Laravel Developers'
  },
  body: `
  <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
  
  <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
  `,
  created_at: "2024-03-30 12:04",
};
</script>

<template>
  <div class="overflow-auto">
    <PostItem :post="post1" />
    <PostItem :post="post2" />
  </div>
</template>
