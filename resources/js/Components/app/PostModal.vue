<script setup>
  import { computed, ref, watch } from 'vue';
  import { XMarkIcon, PaperClipIcon, BookmarkIcon, ArrowUturnLeftIcon } from '@heroicons/vue/24/solid';
  import { SparklesIcon } from '@heroicons/vue/24/outline';
  import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
  } from '@headlessui/vue';
  import PostUserHeader from "./PostUserHeader.vue";
  import { useForm, usePage } from '@inertiajs/vue3';
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  import { isImage } from '@/helpers';
  import axiosClient from '@/axiosClient.js';

  const editor = ClassicEditor;
  const editorConfig = {
    toolbar: ['heading', '|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote']
  };

  const props = defineProps({
    post: {
      type: Object,
      required: true
    },
    group: {
      type: Object,
      default: null
    },
    modelValue: Boolean
  });

  const attachmentExtensions = usePage().props.attachmentExtensions;

  /**
   * {
   *    file: File,
   *    url: ''
   * }
   * @type {Ref<UnwrapRef<*[]>>}
   */
  const attachmentFiles = ref([]);
  const attachmentErrors = ref([]);
  const formErrors = ref({});
  const aiButtonLoading = ref(false);

  const form = useForm({
    body: '',
    group_id: null,
    attachments: [],
    deleted_file_ids: [],
    _method: 'POST'
  });

  const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
  });

  const computedAttachments = computed(() => {
    return [...attachmentFiles.value, ...(props.post.attachments || [])];
  });

  // to display unsupported extension msg
  const showExtensionsText = computed(() => {
    for (let myFile of attachmentFiles.value) {
      const file = myFile.file;
      // parts when a file name => name.svg.png => save svg.png
      let parts = file.name.split('.');
      let ext = parts.pop().toLowerCase();
      // decide to display or not here
      if (!attachmentExtensions.includes(ext)) {
        return true;
      }
    }

    return false;
  });

  const emit = defineEmits(['update:modelValue', 'hide']);

  watch(() => props.post, () => {
    form.body = props.post.body || '';
  });

  function closeModal() {
    show.value = false;
    emit('hide');
    resetModal();
  }

  function resetModal() {
    form.reset();
    formErrors.value = {};
    attachmentFiles.value = [];
    attachmentErrors.value = [];
    if (props.post.attachments) {
      props.post.attachments.forEach(file => file.deleted = false);
    }
  }

  function submit() {
    if (props.group) {
      form.group_id = props.group.id;
    }

    form.attachments = attachmentFiles.value.map((myFile) => myFile.file);
    if (props.post.id) {
      // update
      // with file
      form._method = 'PUT';
      form.post(route('post.update', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
          closeModal();
        },
        onError: (errors) => {
          processErrors(errors);
        }
      });
    } else {
      // create
      form.post(route('post.create'), {
        preserveScroll: true,
        onSuccess: () => {
          closeModal();
        },
        onError: (errors) => {
          processErrors(errors);
        }
      });
    }
  }

  function processErrors(errors) {
    formErrors.value = errors;
    for (const key in errors) {
      if (key.includes('.')) {
        const [, index] = key.split('.');
        attachmentErrors.value[index] = errors[key];
      }
    }
    console.log(errors);
  }

  async function onAttachmentChoose($event) {
    for (const file of $event.target.files) {
      const myFile = {
        file,
        url: await readFile(file)
      };
      attachmentFiles.value.push(myFile);
    }
    $event.target.value = null;
  }

  async function readFile(file) {
    return new Promise((res, rej) => {
      // an image
      if (isImage(file)) {
        const reader = new FileReader();

        reader.onload = () => {
          res(reader.result);
        };
        reader.onerror = rej;
        reader.readAsDataURL(file);
      } else {
        // not image
        res(null);
      }
    });
  }

  function removeFile(myFile) {
    // new file
    if (myFile.file) {
      attachmentFiles.value = attachmentFiles.value.filter(f => f !== myFile);
    } else {
      // existing file
      form.deleted_file_ids.push(myFile.id);
      myFile.deleted = true;
    }
  }

  function undoDelete(myFile) {
    myFile.deleted = false;
    form.deleted_file_ids = form.deleted_file_ids.filter(id => myFile.id !== id);
  }

  function getAIContent() {
    if (!form.body) {
      return;
    }
    aiButtonLoading.value = true;
    axiosClient.post(route('post.aiContent'), {
      prompt: form.body
    })
      .then(({ data }) => {
        // console.log(data.content);
        form.body = data.content;
        aiButtonLoading.value = false;
      })
      .catch(err => {
        console.log(err);
        aiButtonLoading.value = false;
      });
  }

</script>

<template>
  <teleport to="body">
    <TransitionRoot appear :show="show" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-50">
        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
          leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
          <div class="fixed inset-0 bg-black/25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95">

              <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded bg-white text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3"
                  class="flex items-center justify-between py-3 px-4 font-medium bg-gray-100 text-gray-900">

                  {{ post.id ? 'Update Post' : 'Create Post' }}
                  <button @click="closeModal"
                    class="w-8 h-8 rounded-full hover:bg-black/5 transition flex items-center justify-center">
                    <XMarkIcon class="w-4 h-4" />
                  </button>
                </DialogTitle>

                <div class="p-4">
                  <PostUserHeader :post="post" :show-time="false" class="mb-4" />

                  <div v-if="formErrors.group_id" class="bg-red-400 py-2 px-3 rounded text-white mb-3">
                    {{ formErrors.group_id }}
                  </div>

                  <div class="relative group">
                    <ckeditor :editor="editor" v-model="form.body" :config="editorConfig"></ckeditor>

                    <!-- OpenAI btn -->
                    <button @click="getAIContent" :disabled="aiButtonLoading"
                      class="absolute right-1 top-12 w-8 h-8 p-1 rounded bg-indigo-500 hover:bg-indigo-600 text-white flex justify-center items-center transition-all opacity-0 group-hover:opacity-100 disabled:cursor-not-allowed disabled:bg-indigo-400 disabled:hover:bg-indigo-400">
                      <!-- loading spining animation icon -->
                      <svg v-if="aiButtonLoading" class="animate-spin h-4 w-4 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                      </svg>
                      <!-- Sparkles/AI icon -->
                      <SparklesIcon v-else class="w-4 h-4" />
                    </button>

                  </div>

                  <!-- Support Extension Error -->
                  <div v-if="showExtensionsText"
                    class="border-l-4 border-amber-500 py-2 px-3 bg-amber-100 mt-3 text-gray-800">
                    Files must be one of the following extensions
                    <small>{{ attachmentExtensions.join(', ') }}</small>
                  </div>
                  <!--/ End Support Extension Error -->

                  <!-- Total Size Error -->
                  <div v-if="formErrors.attachments"
                    class="border-l-4 border-red-500 py-2 px-3 bg-red-100 mt-3 text-gray-800">
                    {{ formErrors.attachments }}
                  </div>
                  <!--/ End Total Size Error -->

                  <!-- Attachment -->
                  <div class="grid gap-3 my-3" :class="[
                    computedAttachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'
                  ]">
                    <div v-for="(myFile, ind) of computedAttachments">

                      <div
                        class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative border-2"
                        :class="attachmentErrors[ind] ? 'border-red-500' : ''">

                        <div v-if="myFile.deleted"
                          class="absolute z-10 left-0 bottom-0 right-0 py-2 px-3 text-sm bg-black text-white flex justify-between items-center">
                          To be deleted

                          <ArrowUturnLeftIcon @click="undoDelete(myFile)" class="w-4 h-4 cursor-pointer" />
                        </div>

                        <!-- remove attachment -->
                        <button @click="removeFile(myFile)"
                          class="absolute z-20 right-3 top-3 w-7 h-7 flex items-center justify-center bg-black/30 text-white rounded-full hover:bg-black/40">
                          <XMarkIcon class="h-4 w-4" />
                        </button>
                        <!-- /remove attachment -->

                        <img v-if="isImage(myFile.file || myFile)" :src="myFile.url"
                          class="object-contain aspect-square" :class="myFile.deleted ? 'opacity-50' : ''" />

                        <div v-else class="flex flex-col justify-center items-center px-3"
                          :class="myFile.deleted ? 'opacity-50' : ''">
                          <PaperClipIcon class="w-10 h-10 mb-3" />
                          <small class="text-center">{{ (myFile.file || myFile).name }}</small>
                        </div>
                      </div>
                      <small class="text-red-500">{{ attachmentErrors[ind] }}</small>
                    </div>
                  </div>
                  <!-- /Attachment -->
                  <!-- <pre>{{ post.attachments }}</pre> -->

                </div>
                <div class="flex gap-2 py-3 px-4">
                  <button type="button"
                    class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full relative">
                    <PaperClipIcon class="w-4 h-4 mr-2" />
                    Attach Files
                    <input @click.stop @change="onAttachmentChoose" type="file" multiple
                      class="absolute left-0 top-0 right-0 bottom-0 opacity-0">
                  </button>
                  <button type="button"
                    class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full"
                    @click="submit">
                    <BookmarkIcon class="w-4 h-4 mr-2" />
                    Submit
                  </button>
                </div>
              </DialogPanel>

            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </teleport>
</template>
