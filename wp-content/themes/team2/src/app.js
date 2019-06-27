window.baseURL = 'http://data-journalism.alphonsebouy.fr/wp-content/themes/team2/public'

import './config.scss'

import Vue from 'vue'
import Home from './components/Home.vue'
import Single from './components/Single.vue'
import Archive from './components/Archive.vue'


var app = new Vue({
    el: '#app',
    components: {
        Home,
        Single,
        Archive
    }
})

const loader = document.querySelector('.loader')

if (loader) {
    window.addEventListener('load', () => {
        loader.style.display = 'none'
    })
}

