<script setup>
  import { Link } from '@inertiajs/vue3';

  defineProps({
    user: Object,
    forApprove: {
      type: Boolean,
      default: false
    },
    showRoleDropdown: {
      type: Boolean,
      default: false
    },
    disableRoleDropdown: {
      type: Boolean,
      default: false
    }
  });

  defineEmits(['approve', 'reject', 'roleChange', 'delete']);

</script>

<template>
  <div
    class="bg-white dark:bg-slate-900 dark:text-gray-100 transition-all border-2 border-transparent hover:border-indigo-500">
    <div class="flex items-center gap-2 py-2 px-2">
      <!-- Link on avatar icon -->
      <Link :href="route('profile', user.username)">
      <img :src="user.avatar_url" class="w-9 h-9 rounded-full object-cover" alt="" />
      </Link>
      <div class="flex justify-between flex-1">
        <!-- Link on user name -->
        <Link :href="route('profile', user.username)">
        <h3 class="font-black hover:underline">{{ user.name }}</h3>
        </Link>
        <div v-if="forApprove" class="flex gap-1">
          <button class="text-xs py-1 px-2 rounded bg-emerald-500 hover:bg-emerald-600 text-white"
            @click.prevent.stop="$emit('approve', user)">
            approve
          </button>
          <button class="text-xs py-1 px-2 rounded bg-red-500 hover:bg-red-600 text-white"
            @click.prevent.stop="$emit('reject', user)">
            reject
          </button>
        </div>
        <!-- Select input -->
        <div v-if="showRoleDropdown">
          <select @change="$emit('roleChange', user, $event.target.value)"
            class="rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 max-w-xs text-sm leading-6"
            :disabled="disableRoleDropdown">
            <option :selected="user.role === 'admin'">admin</option>
            <option :selected="user.role === 'user'">user</option>
          </select>
          <button @click="$emit('delete', user)"
            class="text-xs py-1.5 px-2 rounded bg-gray-700 dark:bg-red-600 hover:bg-gray-800 dark:hover:bg-red-700 text-white ml-3 disabled:bg-gray-500 dark:disabled:bg-red-400"
            :disabled="disableRoleDropdown">delete</button>
        </div>
      </div>
    </div>
  </div>
</template>
