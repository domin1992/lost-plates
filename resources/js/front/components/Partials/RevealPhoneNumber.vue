<template>
    <div>
        <span v-if="!phoneNumber">tel: <button class="text-purple-heart hover:text-cyan focus:text-cyan" @click="reveal">{{ hiddenPhoneNumber }}</button></span>
        <span v-else>tel: <a :href="`tel:${phoneNumber}`" class="text-purple-heart hover:text-cyan focus:text-cyan">{{ phoneNumber }}</a></span>
    </div>
</template>

<script>
export default {
    props: [
        'markerId',
        'hiddenPhoneNumber',
    ],
    data() {
        return {
            phoneNumber: null,
        };
    },
    methods: {
        reveal() {
            axios.get(`/ajax/markers/${this.markerId}/phone-number`)
                .then((response) => {
                    this.phoneNumber = response.data.phone_number;
                });
        },
    },
}
</script>
