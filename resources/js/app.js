/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',

    created() {

        let id = $("#auth_id").val();
        let url = $("#realtime_url").val();

        // window.Echo.private(`testChannel.${id}`)
        //     .listen('StatusLiked', (e) => {
        //         console.log('thank you');
        //         console.log(e);
        //     });



        window.Echo.private('App.Models.User.' + id)
            .notification((notification) => {

                var test = new Audio('https://soundbible.com/mp3/glass_ping-Go445-1207030150.mp3');

                test.play();

                window.$.ajax({

                    url: url,

                    method: 'post',

                    data: { '_token': $('meta[name="csrf-token"]').attr('content'), notification: notification },

                    success: function(data) {

                        $("#notification_list").prepend(data.view);
                        $("#notification_count").text(data.count);
                        $("#no_notification").remove();
                    }
                });
            });


        let customerUrl = $("#customer_realtime_url").val();
        let customerId = $("#customer_auth_id").val();

        window.Echo.private('App.Models.Customer.' + customerId)
            .notification((notification) => {

                var test = new Audio('https://soundbible.com/mp3/glass_ping-Go445-1207030150.mp3');

                test.play();


                window.$.ajax({

                    url: customerUrl,

                    method: 'post',

                    data: { '_token': $('meta[name="csrf-token"]').attr('content'), notification: notification },

                    success: function(data) {

                        $("#notification_list").prepend(data.view);
                        $("#notification_count").text(data.count);
                        $("#no_notification").remove();

                    }
                });
            });
    }

});