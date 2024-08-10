<script setup>
  import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
  import { PencilIcon, TrashIcon, EllipsisVerticalIcon, EyeIcon } from '@heroicons/vue/20/solid';
  import { ClipboardIcon } from '@heroicons/vue/24/outline';
  import { Link, usePage } from '@inertiajs/vue3';
  import { computed } from 'vue';

  const props = defineProps({
    post: {
      type: Object,
      default: null
    },
    comment: {
      type: Object,
      default: null
    }
  });

  const authUser = usePage().props.auth.user;

  // we check comment first here 'cos post will always provided/avail
  const user = computed(() => props.comment?.user || props.post?.user);

  // curr user owner of the either post or comment
  const editAllowed = computed(() => user.value.id === authUser.id);

  const deleteAllowed = computed(() => {
    // curr user owner of the either post or comment
    if (user.value.id === authUser.id) return true;

    // if curr-user is owner of post return true, means render dropdown at post & comment (post owner can delete all comments)
    if (props.post.user.id === authUser.id) return true;

    // dropdown rendered for post only
    return !props.comment && props.post.group?.role === 'admin';
  });


  defineEmits(['edit', 'delete']);

  function copyToClipboard() {
    // Get the text content of the element
    const textToCopy = route('post.view', props.post.id);

    // Create a temporary textarea element
    const textarea = document.createElement('textarea');
    textarea.value = textToCopy;

    // Add the textarea to the DOM
    document.body.appendChild(textarea);

    // Select the text inside the textarea
    textarea.select();

    try {
      // Copy the text to the clipboard
      document.execCommand('copy');
      this.copySuccess = 'Text copied to clipboard!';
    } catch (err) {
      console.error('Failed to copy text', err);
      this.copySuccess = 'Failed to copy text.';
    }

    // Remove the textarea from the DOM
    document.body.removeChild(textarea);
  }
</script>

<template>
  <Menu as="div" class="relative inline-block text-left">
    <div>
      <MenuButton class="w-8 h-8 z-10 rounded-full hover:bg-black/5 transition flex items-center justify-center">
        <EllipsisVerticalIcon class="w-5 h-5" aria-hidden="true" />
      </MenuButton>
    </div>

    <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in"
      leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
      <MenuItems
        class="absolute z-20 right-0 mt-2 w-44 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
        <div class="px-1 py-1">

          <!-- open post menu -->
          <MenuItem v-slot="{ active }">
          <Link :href="route('post.view', post.id)" :class="[
            active ? 'bg-indigo-500 text-white' : 'text-gray-900',
            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
          ]">
          <EyeIcon class="mr-2 h-5 w-5" aria-hidden="true" />
          Open Post
          </Link>
          </MenuItem>

          <!-- copy to clipboard menu -->
          <MenuItem v-slot="{ active }">
          <button @click="copyToClipboard" :class="[
            active ? 'bg-indigo-500 text-white' : 'text-gray-900',
            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
          ]">
            <ClipboardIcon class="mr-2 h-5 w-5" aria-hidden="true" />
            Copy Post URL
          </button>
          </MenuItem>

          <!-- edit menu -->
          <MenuItem v-if="editAllowed" v-slot="{ active }">
          <button @click="$emit('edit')" :class="[
            active ? 'bg-indigo-500 text-white' : 'text-gray-900',
            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
          ]">
            <PencilIcon class="mr-2 h-5 w-5" aria-hidden="true" />
            Edit
          </button>
          </MenuItem>

          <!-- delete menu -->
          <MenuItem v-if="deleteAllowed" v-slot="{ active }">
          <button @click="$emit('delete')" :class="[
            active ? 'bg-indigo-500 text-white' : 'text-gray-900',
            'group flex w-full items-center rounded-md px-2 py-2 text-sm',
          ]">
            <TrashIcon class="mr-2 h-5 w-5" aria-hidden="true" />
            Delete
          </button>
          </MenuItem>

        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>

<style scoped></style>
