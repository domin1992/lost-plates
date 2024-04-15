<template>
    <div>
        <Dropdown
            v-model="selectedLanguage"
            :options="languages"
            optionLabel="name"
            :placeholder="selectedLanguageName"
            class="w-full md:w-[10rem]"
        />
    </div>
</template>

<script setup lang="ts">

import { ConfigService } from '@front/services/ConfigService';
import { emitter } from '@front/utils/eventBus';
import type { AxiosInstance } from 'axios';
import type { I18n } from 'laravel-vue-i18n';
import Dropdown from 'primevue/dropdown';
import { computed, inject, onMounted, ref, toRaw, watch } from 'vue';

const i18n = inject<I18n>('i18n');
const axios = inject<AxiosInstance>('axios');
const configService = inject<ConfigService>('configService');

const selectedLanguage = ref<{name: string; value: string} | null>(null);
const blockedLangUpdate = ref<boolean>(false);

onMounted(() => {
    emitter.on('languageChanged', ({lang}) => {
        if (!blockedLangUpdate.value) {
            selectedLanguage.value = languages.value.find((language) => language.value === lang) || null;
        }
    });

    selectedLanguage.value = languages.value.find((language) => language.value === i18n?.getActiveLanguage()) || null;
});

const selectedLanguageName = computed<string>((): string => {
    const foundLanguage = languages.value.find((_language) => _language.value === selectedLanguage.value?.value);

    return foundLanguage?.name || '';
});

const languages = computed<{name: string; value: string}[]>((): {name: string; value: string}[] => {
    const allowedLanguages = configService?.getAllowedLanguages();

    if (!allowedLanguages) return [];

    return [...Object.entries(allowedLanguages)].map(([key, value]) => ({
        name: value as string,
        value: key,
    }));
});

watch(selectedLanguage, (value: any) => {
    value = toRaw(value);
    if (i18n?.getActiveLanguage() !== value.value) {
        setTimeout(() => {
            i18n?.loadLanguageAsync(value.value);
            if (axios) {
                axios.defaults.headers['Accept-Language'] = value.value;
            }
        }, 500);

        blockedLangUpdate.value = true;
        emitter.emit('languageChanged', { lang: value.value });

        setTimeout(() => blockedLangUpdate.value = false, 500);
    }
});

</script>
