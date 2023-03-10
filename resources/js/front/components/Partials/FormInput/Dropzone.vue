<template>
    <div>
        <div
            :class="['bg-white', 'rounded', 'flex-1', 'w-full', 'py-10', 'border', 'border-dashed', 'cursor-pointer', 'relative', {'border-black' : !isDragOver}, {'border-purple-heart' : isDragOver}]"
            @click="openFileExplorer"
            ref="dropzone"
        >
            <div class="flex flex-row items-center justify-center w-full" v-if="!files.length">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-[1.25rem] block" viewBox="0 0 640 512"><path d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4zM393.4 288H328v112c0 8.8-7.2 16-16 16h-48c-8.8 0-16-7.2-16-16V288h-65.4c-14.3 0-21.4-17.2-11.3-27.3l105.4-105.4c6.2-6.2 16.4-6.2 22.6 0l105.4 105.4c10.1 10.1 2.9 27.3-11.3 27.3z"/></svg> <span class="block ml-2">Dodaj zdjęcia</span>
            </div>
            <input
                type="file"
                class="hidden w-0 absolute"
                accept="image/*"
                multiple
                ref="fileInput"
                @change="handleFileInputChange"
            >
            <div :class="['flex', 'flex-row', 'flex-wrap', 'mx-2', '-mt-4', {'hidden' : !files.length}]" ref="sortableField">
                <div
                    class="basis-1/2 sm:basis-1/3 md:basis-1/3 lg:basis-1/4 px-2 mt-4 cursor-grab"
                    v-for="file in files"
                    :key="file.id"
                    :data-file-id="file.id"
                >
                    <div class="w-full pt-[100%] bg-link-water relative rounded">
                        <button type="button" class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 z-10 js-delete-button" v-if="!file.uploading" @click.stop="deleteMedia(file.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-[1.5rem]" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"/></svg>
                        </button>
                        <img :src="file.src" class="object-cover absolute top-1/2 left-1/2 h-full w-full -translate-x-1/2 -translate-y-1/2 rounded" v-if="file.src && !file.uploading">
                        <div class="absolute top-0 left-0 w-full h-full p-4" v-if="file.uploading">
                            <span class="block text-xs">{{ file.name }}</span>
                            <div class="w-full bg-white rounded-full mt-2 overflow-hidden">
                                <div class="bg-purple-heart text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-l-full transition-[width]" :style="{ width: `${file.uploadingProgress}%` }">{{ file.uploadingProgress }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="media" :value="mediaIds">
        </div>
        <div class="mt-1 text-red-600 text-sm text-bold" v-if="errorMessage">{{ Array.isArray(errorMessage) && errorMessage[0] !== undefined ? errorMessage[0] : (errorMessage !== undefined ? errorMessage : "") }}</div>
        <div class="mt-1 text-gray-600 text-xs text-bold" v-if="!errorMessage && description">{{ description }}</div>
    </div>
</template>

<script>
import Sortable from 'sortablejs';
export default {
    props: [
        'mediaType',
        'errorMessage',
        'description',
    ],
    data() {
        return {
            isDragOver: false,
            files: [],
            sortable: null,
        };
    },
    mounted() {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
            this.$refs.dropzone.addEventListener(eventName, this.handleDragEvent);
        });

        this.sortable = Sortable.create(this.$refs.sortableField, {
            onEnd: (e) => {
                const item = e.item;
                const fileId = item.dataset.fileId;

                if (e.oldIndex !== e.newIndex) {
                    this.files.forEach((file) => {
                        if (file.id === fileId) {
                            file.position = e.newIndex;
                        }
                    });

                    if (e.oldIndex < e.newIndex) {
                        this.files.forEach((file) => {
                            if (file.id !== fileId) {
                                file.position--;
                            }
                        });
                    } else {
                        this.files.forEach((file) => {
                            if (file.id !== fileId) {
                                file.position++;
                            }
                        });
                    }
                }
            },
        });
    },
    methods: {
        openFileExplorer(e) {
            if (e.target.matches(".js-delete-button") || e.target.closest(".js-delete-button")) {
                return;
            }

            this.$refs.fileInput.click();
        },
        handleDragEvent(e) {
            e.preventDefault();
            e.stopPropagation();

            if (e.type === "dragenter" || e.type === "dragover") {
                this.isDragOver = true;
            } else if (e.type === "dragleave" || e.type === "drop") {
                this.isDragOver = false;
            }

            if (e.type === "drop") {
                const files = e.dataTransfer.files;
                
                [...files].forEach((file) => {
                    this.files.push({
                        uploading: true,
                        uploadingProgress: 0,
                        id: `tmp-${this.files.length + 1}`,
                        name: file.name,
                        src: null,
                        file: file,
                        position: this.files.length,
                    });
                });

                this.runUploader();
            }
        },
        handleFileInputChange(e) {
            const files = this.$refs.fileInput.files;

            [...files].forEach((file) => {
                this.files.push({
                    uploading: true,
                    uploadingProgress: 0,
                    id: `tmp-${this.files.length}`,
                    name: file.name,
                    src: null,
                    file: file,
                    position: this.files.length,
                });
            });

            this.runUploader();
        },
        runUploader() {
            this.files.forEach((file, index) => {
                if (file.uploading && file.file) {
                    const formData = new FormData();
                    formData.append('file', file.file);
                    formData.append('type', 'image');
                    formData.append('image_type', this.mediaType);
                    this.files[index].file = null;

                    axios.post('/ajax/media', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                        onUploadProgress: (progressEvent) => {
                            const event = progressEvent.event;
                            const totalLength = event.lengthComputable
                                ? event.total
                                : event.target.getResponseHeader('content-length')
                                    || event.target.getResponseHeader('x-decompressed-content-length');

                            if (totalLength) {
                                this.files[index].uploadingProgress = Math.round((progressEvent.loaded * 100) / totalLength);
                            }
                        },
                    }).then((response) => {
                        this.files[index].src = response.data.media.url.miniature;
                        this.files[index].id = response.data.media.id;
                        this.files[index].uploading = false;
                        this.$emit('updated', this.mediaIds());
                    });
                }
            })
        },
        deleteMedia(fileId) {
            this.files = this.files.filter((file) => file.id !== fileId);
            this.$emit('updated', this.mediaIds());
        },
        mediaIds() {
            const sorted = this.files.sort((a, b) => a.position - b.position);
            return sorted.map((file) => file.id);
        },
    },
}
</script>
