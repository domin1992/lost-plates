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
                    :errorMessage="errors.contact !== undefined ? errors.contact : null"
                />
            </div>
            <button class="block h-[2.375rem] leading-[2.25rem] px-4 ml-4 mt-[1.75rem] bg-purple-heart text-white transition-colors rounded hover:bg-cyan focus-visible:bg-cyan" @click="submit">{{ $t('common.submit') }}</button>
        </div>
        <div class="mt-4" v-else>
            <Alert type="success" customClasses="w-full" :message="successMessage" />
        </div>
    </div>
</template>

<script>
import { defineAsyncComponent } from 'vue';
export default {
    components: {
        Alert: defineAsyncComponent(() => import('./../Partials/Alert.vue')),
        FormInputText: defineAsyncComponent(() => import('./../Partials/FormInput/FormInputText.vue')),
    },
    props: [
        'markerId',
    ],
    data() {
        return {
            contact: '',
            errors: {},
            successMessage: '',
        };
    },
    methods: {
        submit() {
            this.errors = {};
            this.successMessage = '';

            axios.post(`/ajax/markers/${this.markerId}/contact`, {
                contact: this.contact,
            })
                .then((response) => {
                    this.successMessage = response.data.message;
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
        },
    },
}
</script>
