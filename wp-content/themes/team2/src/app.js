window.baseURL = 'http://localhost/data-journalism/wp-content/themes/team2/public'

import './config.css'

import Vue from 'vue'
import Home from './components/Home.vue'
import Single from './components/Single.vue'


var app = new Vue({
    el: '#app',
    components: {
        Home,
        Single
    }
})

