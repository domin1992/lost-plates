require('./bootstrap');
import Vue from 'vue';
window.$ = window.jQuery = require('jquery');
require('popper.js');
require('bootstrap');
require('owl.carousel');
window.moment = require('moment');
require('jquery-toast-plugin');

Vue.component('big-map', require('./components/BigMap.vue').default);
Vue.component('add-marker-form', require('./components/AddMarkerForm.vue').default);
Vue.component('map-markers-list', require('./components/MapMarkersList.vue').default);
Vue.component('images-gallery', require('./components/ImagesGallery.vue').default);

const app = window.app = new Vue({
    el: '#lost-plates',
    data: {},
    methods: {
        initMap(){
            this.$emit('init-map', {});
        },
        scrollTo(elem){
            $('html, body').animate({
                scrollTop: $(elem).offset().top - 50
            }, 500);
        },
        waitForEl(selector, callback){
            var self = this;
            if($(selector).length){
                callback();
            }else{
                setTimeout(function(){
                    self.waitForEl(selector, callback);
                }, 100);
            }
        },
        toast(type, msg){
            var bgColor = '#9F0C00';
            if(type == 'success'){
                var bgColor = '#00BB51';
            }
            else if(type == 'warning'){
                var bgColor = '#B79405';
            }
            else if(type == 'danger'){
                var bgColor = '#9F0C00';
            }

            $.toast({
                text: msg,
                showHideTransition: 'slide',
                hideAfter: 5000,
                allowToastClose: true,
                position: 'bottom-center',
                bgColor: bgColor,
                textColor: '#FFFFFF',
            });
        },
    },
});

$(document).ready(() => {
    $('[data-toggle="tooltip"]').tooltip();
});