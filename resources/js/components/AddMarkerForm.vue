<template>
    <div class="modal fade" id="addMarkerFormModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dodaj pineskę</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select v-bind:class="['form-control', {'is-invalid' : undefined !== errors.type}]" id="type" name="type" v-model="type" v-on:change="typeChanged()">
                            <option value="lost">Zgubiłem</option>
                            <option value="found">Znalazłem</option>
                        </select>
                        <div class="invalid-feedback" v-if="undefined !== errors.type">
                            {{ errors.type[0] }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="plate_number">Numer tablicy rejestracyjnej</label>
                        <input type="text" v-bind:class="['form-control', {'is-invalid' : undefined !== errors.plate_number}]" id="plate_number" name="plate_number" v-model="plateNumber">
                        <div class="invalid-feedback" v-if="undefined !== errors.plate_number">
                            {{ errors.plate_number[0] }}
                        </div>
                    </div>
                    <div v-if="type == 'lost'">
                        <div class="form-group">
                            <label for="radius">W odległości (opcjonalne)</label>
                            <div v-bind:class="['input-group', {'is-invalid' : undefined !== errors.radius}]">
                                <input type="text" v-bind:class="['form-control', {'is-invalid' : undefined !== errors.radius}]" id="radius" name="radius" v-model="radius">
                                <div class="input-group-append">
                                    <span class="input-group-text">km</span>
                                </div>
                            </div>
                            <div class="invalid-feedback" v-if="undefined !== errors.radius">
                                {{ errors.radius[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Numer telefonu</label>
                            <input type="text" v-bind:class="['form-control', {'is-invalid' : undefined !== errors.phone_number}]" id="phone_number" name="phone_number" v-model="phoneNumber">
                            <div class="invalid-feedback" v-if="undefined !== errors.phone_number">
                                {{ errors.phone_number[0] }}
                            </div>
                            <small class="form-text text-muted" v-else>Numer telefonu będzie niewidoczny dla robotów skanujących strony.</small>
                        </div>
                        <div class="form-group">
                            <label for="additional_info">Dodatkowe informacje (opcjonalnie)</label>
                            <textarea v-bind:class="['form-control', {'is-invalid' : undefined !== errors.additional_info}]" id="additional_info" name="additional_info" rows="2" v-model="additionalInfo"></textarea>
                            <div class="invalid-feedback" v-if="undefined !== errors.additional_info">
                                {{ errors.additional_info[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" v-bind:class="['custom-control-input', {'is-invalid' : undefined !== errors.notify_when_found}]" id="notify_when_found" name="notify_when_found" v-model="notifyWhenFound">
                                <label class="custom-control-label" for="notify_when_found">
                                    Powiadom mnie jak moja tablica się znajdzie
                                    <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="Gdy ktoś doda informacje o znalezionych tablicach rejestracyjnych o tym numerze zostaniesz niezwłocznie powiadomiony mailowo"></i>
                                </label>
                                <div class="invalid-feedback" v-if="undefined !== errors.notify_when_found">
                                    {{ errors.notify_when_found[0] }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group" v-if="notifyWhenFound">
                            <label for="email">Adres e-mail</label>
                            <input type="email" v-bind:class="['form-control', {'is-invalid' : undefined !== errors.email}]" id="email" name="email" v-model="email">
                            <div class="invalid-feedback" v-if="undefined !== errors.email">
                                {{ errors.email[0] }}
                            </div>
                            <small class="form-text text-muted" v-else>Adres e-mail tylko do wiadomości administratora strony.</small>
                        </div>
                    </div>
                    <div v-if="type == 'found'">
                        <div class="form-group">
                            <label for="phone_number">Numer telefonu (opcjonalnie)</label>
                            <input type="text" v-bind:class="['form-control', {'is-invalid' : undefined !== errors.phone_number}]" id="phone_number" name="phone_number" v-model="phoneNumber">
                            <div class="invalid-feedback" v-if="undefined !== errors.phone_number">
                                {{ errors.phone_number[0] }}
                            </div>
                            <small class="form-text text-muted" v-else>Numer telefonu będzie niewidoczny dla robotów skanujących strony.</small>
                        </div>
                        <div class="form-group">
                            <label for="email">Adres e-mail (opcjonalnie)</label>
                            <input type="email" v-bind:class="['form-control', {'is-invalid' : undefined !== errors.email}]" id="email" name="email" v-model="email">
                            <div class="invalid-feedback" v-if="undefined !== errors.email">
                                {{ errors.email[0] }}
                            </div>
                            <small class="form-text text-muted" v-else>Adres e-mail będzie niewidoczny dla robotów skanujących strony.</small>
                        </div>
                        <div class="form-group">
                            <label for="additional_info">Dodatkowe informacje (opcjonalnie)</label>
                            <textarea v-bind:class="['form-control', {'is-invalid' : undefined !== errors.additional_info}]" id="additional_info" name="additional_info" rows="2" v-model="additionalInfo"></textarea>
                            <div class="invalid-feedback" v-if="undefined !== errors.additional_info">
                                {{ errors.additional_info[0] }}
                            </div>
                            <small class="form-text text-muted" v-else>Podpowiedz gdzie znajduje się ta tablica rejestracyjna, aby uławić poszukiwania.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                    <button type="button" class="btn btn-primary" v-on:click="storeMarker()">Dodaj</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [],
        data(){
            return {
                lat: null,
                lng: null,
                type: 'lost',
                plateNumber: '',
                phoneNumber: '',
                additionalInfo: '',
                notifyWhenFound: false,
                email: '',
                radius: '',
                errors: {},
            };
        },
        created(){

        },
        mounted(){
            this.$root.$on('show-add-marker-form', (params) => this.openModal(params));
        },
        methods: {
            openModal(params = {}){
                $('#addMarkerFormModal').modal('show');
                this.lat = params.lat;
                this.lng = params.lng;
            },
            storeMarker(){
                let self = this;
                self.errors = {};

                axios.post('/ajax/markers', {
                    lat: self.lat,
                    lng: self.lng,
                    type: self.type,
                    plate_number: self.plateNumber,
                    phone_number: self.phoneNumber,
                    additional_info: self.additionalInfo,
                    notify_when_found: self.notifyWhenFound,
                    email: self.email,
                    radius: self.radius,
                })
                .then(response => {
                    $('#addMarkerFormModal').modal('hide');
                    self.$root.toast('success', response.data.message);
                    self.lat = null;
                    self.lng = null;
                    self.type = 'lost';
                    self.plateNumber = '';
                    self.phoneNumber = '';
                    self.additionalInfo = '';
                    self.notifyWhenFound = false;
                    self.email = false;
                    self.radius = false;
                    self.$root.$emit('refresh-map', {});
                })
                .catch(errors => {
                    if(undefined !== errors.response.data.errors){
                        self.errors = errors.response.data.errors;
                    }
                });
            },
            typeChanged(){
                this.errors = {};
            },
        },
    }
</script>