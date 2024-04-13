<template>
    <div>
        <div class="mt-4 flex" v-if="!successMessage">
            <div class="flex-1">
                <FormInputText
                    :label="$t('emailContactForm.askForContactViaEmail')"
                    name="contact"
                    v-model="contact"
                    :placeholder="$t('emailContactForm.yourEmailOrPhoneNumber')"
                    :required="false"
                    :errorMessage="$messageBagFirstError(errors, 'contact')"
                />
            </div>
            <Button class="self-end ml-1" @click="submit">{{ $t('common.submit') }}</button>
        </div>
        <div class="mt-4" v-else>
            <Alert severity="success" :closable="false">{{ successMessage }}</Alert>
        </div>
    </div>
</template>

<script setup lang="ts">

import type { ApiRequestFailedException } from "@front/exceptions/ApiRequestFailedException";
import { type MessageBagError } from "@front/interfaces/MessageBagError";
import { useMarkersStore } from "@front/stores/markers";
import { defineAsyncComponent, ref } from "vue";

const FormInputText = defineAsyncComponent(() => import('./../partials/form-input/FormInputText.vue'));
const Button = defineAsyncComponent(() => import('./../partials/Button.vue'));
const Alert = defineAsyncComponent(() => import('./../partials/Alert.vue'));

const markersStore = useMarkersStore();

const successMessage = ref<string | null>(null);
const contact = ref<string>('');
const errors = ref<MessageBagError>({});

const submit = (): void => {
    if (!markersStore.selectedMarker) return;

    markersStore.submitContact(markersStore.selectedMarker.id, contact.value)
        .then((message: string | null) => {
            successMessage.value = message;
        })
        .catch((error: ApiRequestFailedException) => errors.value = error.getMessageBagError());
};

</script>
