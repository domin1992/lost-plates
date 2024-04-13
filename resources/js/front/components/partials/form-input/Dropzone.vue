<template>
    <div>
        <label :for="name" :class="['font-bold text-sm mb-1', {'text-red-600' : errorMessage}]" v-if="label">{{ label }}</label>
        <div
            :class="['bg-white rounded flex-1 w-full py-10 border border-dashed cursor-pointer relative mt-1', {'border-black' : !isDragOver}, {'border-chambray' : isDragOver}]"
            @click="openFileExplorer"
            ref="dropzone"
        >
            <div class="flex flex-row items-center justify-center w-full" v-if="!files.length">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-[1.25rem] block" viewBox="0 0 640 512"><path d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4zM393.4 288H328v112c0 8.8-7.2 16-16 16h-48c-8.8 0-16-7.2-16-16V288h-65.4c-14.3 0-21.4-17.2-11.3-27.3l105.4-105.4c6.2-6.2 16.4-6.2 22.6 0l105.4 105.4c10.1 10.1 2.9 27.3-11.3 27.3z"/></svg> <span class="block ml-2">{{ dropzoneLabel() }}</span>
            </div>

            <input
                type="file"
                class="hidden w-0 absolute"
                :accept="acceptFileTypes()"
                :multiple="filesCount !== undefined && filesCount > 1"
                ref="fileInput"
                @change="handleFileInputChange"
            >

            <div
                :class="[
                    'flex flex-row flex-wrap mx-2 -mt-4',
                    {'hidden': !files.length},
                    {'justify-center': filesCount && filesCount === 1}
                ]"
                ref="sortableField"
            >
                <div
                    class="basis-1/2 sm:basis-1/3 md:basis-1/3 lg:basis-1/4 px-2 mt-4 cursor-grab"
                    v-for="(file, index) in files"
                    :key="file.id"
                    :data-file-id="file.id"
                >
                    <div class="w-full pt-[100%] bg-link-water relative rounded">
                        <button type="button" class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 z-10 js-delete-button" v-if="!file.uploading" @click="deleteMedia(index)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-[1.5rem]" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"/></svg>
                        </button>

                        <img :src="file.url.original" class="object-cover absolute top-1/2 left-1/2 h-full w-full -translate-x-1/2 -translate-y-1/2 rounded" v-if="!file.uploading && file.uploadingProgress === 100">

                        <div class="absolute top-0 left-0 w-full h-full p-4" v-if="file.uploading">
                            <div class="w-full bg-white rounded-full mt-2 overflow-hidden">
                                <div class="bg-chambray text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-l-full transition-[width]" :style="{ width: `${file.uploadingProgress}%` }">{{ file.uploadingProgress }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { MediaService } from "@front/services/MediaService";
import type { TurnstileService } from "@front/services/TurnstileService";
import type { DropzoneMedia } from "@front/interfaces/DropzoneMedia";
import type { Media } from "@front/interfaces/Media";
import { trans } from "laravel-vue-i18n";
import { inject, onMounted, ref, watchEffect } from "vue";
import Sortable from 'sortablejs';

const mediaService = inject<MediaService>('mediaService');
const turnstileService = inject<TurnstileService>('turnstileService');

const emit = defineEmits(['change']);

const props = defineProps<{
    name: string,
    label?: string,
    required: boolean,
    filesCount: number,
    fileType: string,
    imageType: string,
    initialMediaIds?: string[],
    errorMessage: string,
}>();

const dropzone = ref<HTMLElement|null>(null);
const fileInput = ref<HTMLElement|null>(null);
const sortableField = ref<HTMLElement|null>(null);
const files = ref<DropzoneMedia[]>([]);
const isDragOver = ref<boolean>(false);
const sortable = ref<Sortable|null>(null);

onMounted(() => {
    props.initialMediaIds?.forEach((mediaId: string | null, index: number) => {
        if (!mediaId) return;
        appendFileByMediaId(mediaId, index);
    });
});

watchEffect(() => {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
        if (dropzone.value) {
            dropzone.value.addEventListener(eventName, handleDragEvent);
        }
    });

    if (sortableField.value) {
        sortable.value = Sortable.create(sortableField.value, {
            onEnd: (e: any) => {
                const item = e.item;
                const fileId = item.dataset.fileId;

                if (e.oldIndex !== e.newIndex) {
                    if (e.oldIndex < e.newIndex) {
                        files.value.forEach((file) => {
                            if (file.id !== fileId && file.position <= e.newIndex) {
                                file.position--;
                            }
                        });
                    } else {
                        files.value.forEach((file) => {
                            if (file.id !== fileId && file.position >= e.newIndex) {
                                file.position++;
                            }
                        });
                    }

                    const movedFile = files.value.find((file) => file.id === fileId);
                    if (movedFile) {
                        movedFile.position = e.newIndex;
                    }

                    emitChange();
                }

            },
        });
    }
});

const acceptFileTypes = (): string => {
    return props.fileType === 'image' ? 'image/*' : '*';
};

const dropzoneLabel = (): string => {
    return props.fileType === 'image'
        ? (
            props.filesCount === 1
            ? trans('form.uploadImage')
            : trans('form.uploadImages')
        )
        : (
            props.filesCount === 1
            ? trans('form.uploadFile')
            : trans('form.uploadFiles')
        );
};

const openFileExplorer = (event: any): void => {
    const { target } = event;

    if (target.matches('.js-delete-button') || target.closest('.js-delete-button')) return;

    fileInput.value?.click();
};

const handleDragEvent = async (event: any): Promise<void> => {
    event.preventDefault();
    event.stopPropagation();

    if (!mediaService) return;

    if (event.type === "dragenter" || event.type === "dragover") {
        isDragOver.value = true;
    } else if (event.type === "dragleave" || event.type === "drop") {
        isDragOver.value = false;
    }

    if (event.type === "drop") {
        for (let i = 0; i < [...event.dataTransfer.files].length; i++) {
            const cfTurnstileResponse = turnstileService?.getToken();
            if (!cfTurnstileResponse) return;

            const response = await mediaService.store(
                event.dataTransfer.files[i],
                props.fileType ? props.fileType : '',
                props.imageType,
                cfTurnstileResponse
            );

            turnstileService?.refresh();

            appendFileFromMedia(response.data.media, i);
        }
    }
};

const handleFileInputChange = async (event: any): Promise<void> => {
    if (!mediaService) return;

    if (!props.fileType) {
        console.error('File type is not defined');
        return;
    }

    if (props.filesCount && props.filesCount === 1) {
        clearFiles();
    }

    if (files.value.length >= props.filesCount) {
        return;
    }

    for (let i = 0; i < event.target.files.length; i++) {
        const cfTurnstileResponse = turnstileService?.getToken();
        if (!cfTurnstileResponse) return;

        const response = await mediaService.store(
            event.target.files[i],
            props.fileType ? props.fileType : '',
            props.imageType,
            cfTurnstileResponse
        );

        turnstileService?.refresh();

        appendFileFromMedia(response.data.media, i);
    }
};

const deleteMedia = (index: number): void => {
    files.value.splice(index, 1);

    emitChange();
};

const clearFiles = (): void => {
    files.value = [];

    emitChange();
};

const appendFileByMediaId = (mediaId: string, index: number): void => {
    if (!mediaService) return;

    mediaService.show(mediaId).then(({ data }) => {
        appendFileFromMedia(data.media, index);
    });
};

const appendFileFromMedia = (media: Media, index: number): void => {
    files.value.push({
        id: media.id,
        userId: media.userId,
        fileType: media.fileType,
        imageType: media.imageType,
        fileName: media.fileName,
        fileExtension: media.fileExtension,
        url: media.url,
        position: index,
        uploading: false,
        uploadingProgress: 100,
    } as DropzoneMedia);

    emitChange();
};

const emitChange = () => {
    emit(
        'change',
        files.value
            .sort((a: DropzoneMedia, b: DropzoneMedia) => a.position - b.position)
            .map((file: DropzoneMedia) => file.id)
    );
};
</script>
