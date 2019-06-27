<template>
    <div>
        <div class="home">
            <Header :home-url="homeUrl"></Header>
            <div class="title">
                <h1>Prêts à découvrir des alternatives à la voiture et à l'avion ?</h1>
                <p>
                    Grâce à <strong>Feuille de Route</strong> vous pouvez découvrir toutes les informations sur le developpement durable et les transports, et choisir la meilleur alternative aux transports polluants pour votre trajet !
                </p>
            </div>
            <p class="indicator indicator-1">1) Choisissez votre type de trajet</p>
            <div class="transport-types">
                <input name="serviceType" type="radio" @change="changeServiceType" id="quotidien" checked>
                <h2><label for="quotidien">Trajets du quotidien en Ile de France</label></h2>
                <input name="serviceType" type="radio" @change="changeServiceType" id="france">
                <h2><label for="france">Trajets sur la France</label></h2>
            </div>
            <p class="indicator indicator-2">2) Amusez-vous avec les filtres</p>
            <p class="indicator indicator-3">3) Apprenez plein de choses grâce à nos articles</p>
            <div class="tool">
                <div class="sidebar-left" :class="{'flex-start': endPointGeometry.lat !== null}">
                    <div class="locations">
                        <div class="field">
                            <h3><label for="depart">Mon point de départ</label></h3>
                            <input placeholder="Exemple : Place de la Nation" type="text" name="depart" id="depart" ref="autocompleteStart">
                        </div>
                        <div v-if="startingPointGeometry.lat !== null" class="field">
                            <h3><label for="arrivee">Mon point d'arrivée</label></h3>
                            <input placeholder="Exemple : Rue du Faubourg Saint-Honoré" type="text" name="arrivee" id="arrivee" ref="autocompleteEnd">
                        </div>
                    </div>
                    <div v-if="endPointGeometry.lat !== null">
                        <h3>Filtres</h3>
                        <div class="filters filters-quotidien" :class="{hidden: franceSelected}">
                            <input id="parking" type="checkbox" @change="_e => loadParkingVelo(_e)">
                            <label for="parking">Voir les parkings à vélo</label>
                            <input id="veloLibres" type="checkbox" @change="_e => loadVeloLibres(_e)">
                            <label for="veloLibres">Voir les vélos en libre service</label>
                            <input id="garesidf" type="checkbox" @change="_e => loadGaresIdf(_e)">
                            <label for="garesidf">Voir les gares du réseau ferroviaire</label>
                        </div>
                        <div class="filters filters-france" :class="{hidden: quotidienSelected}">
                            <input id="covoit" type="checkbox" @change="_e => loadCovoit(_e)">
                            <label for="covoit">Voir les aires de covoiturages</label>
                            <input id="elec" type="checkbox" @change="_e => loadElec(_e)">
                            <label for="elec">Voir les bornes de rechargement pour voiture electrique</label>
                            <input id="garesfr" type="checkbox" @change="_e => loadGaresFrance(_e)">
                            <label for="garesfr">Voir les gares du réseau ferroviaire</label>
                        </div>
                    </div>
                    <!-- <p>Rien ne s'affiche ? Essayez de chosir un point de départ et de destination !</p> -->
                </div>
                <div id="map"></div>
                <div class="sidebar-right">
                    <div class="article-preview" ref="preview">
                        <h3>{{replaceQuote(displayedPost.title)}}</h3>
                        <p>{{displayedPost.incent}}</p>
                        <img :src="displayedPost.thumbnail_url" alt="">
                        <a :href="displayedPost.permalink">En savoir plus</a>
                    </div>
                    <div class="dl-kml">
                        <p>Téléchargez notre fichier KML pour l'ajouter à votre application de plan préférée !</p>
                        <button disabled title="Bientôt disponible">Télécharger</button>
                        <a target="_blank" rel="noopener" href="https://support.google.com/mymaps/answer/3024836?co=GENIE.Platform%3DDesktop&hl=en">Comment l'ajouter ?</a>
                    </div>
                </div>
            </div>
            <div class="share">
                <a href="">
                    <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.7488 0.00603394L17.9882 0C13.7633 0 11.033 2.80125 11.033 7.13694V10.4275H7.25184C6.92511 10.4275 6.66052 10.6924 6.66052 11.0192V15.7869C6.66052 16.1136 6.92541 16.3782 7.25184 16.3782H11.033V28.4087C11.033 28.7354 11.2976 29 11.6243 29H16.5576C16.8843 29 17.1489 28.7351 17.1489 28.4087V16.3782H21.5699C21.8967 16.3782 22.1612 16.1136 22.1612 15.7869L22.1631 11.0192C22.1631 10.8623 22.1006 10.712 21.9899 10.601C21.8792 10.49 21.7283 10.4275 21.5714 10.4275H17.1489V7.63806C17.1489 6.29732 17.4684 5.61669 19.2149 5.61669L21.7482 5.61578C22.0747 5.61578 22.3392 5.35089 22.3392 5.02446V0.59736C22.3392 0.271225 22.075 0.00663733 21.7488 0.00603394Z" fill="white"/>
                    </svg>
                </a>
                <a href="">
                    <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M29 5.50894C27.9326 5.9819 26.7873 6.30265 25.584 6.44585C26.8127 5.7101 27.7532 4.54308 28.1989 3.15677C27.0464 3.83813 25.7743 4.33288 24.4188 4.60108C23.3333 3.44312 21.7893 2.72186 20.0768 2.72186C16.7914 2.72186 14.1275 5.38574 14.1275 8.66938C14.1275 9.13508 14.1801 9.58994 14.2816 10.0249C9.338 9.77659 4.9544 7.40811 2.02052 3.80913C1.50767 4.68623 1.21592 5.70825 1.21592 6.79921C1.21592 8.86328 2.26698 10.6845 3.86169 11.75C2.88673 11.7174 1.96977 11.4492 1.16701 11.0034V11.0777C1.16701 13.959 3.21838 16.3638 5.93841 16.9111C5.44006 17.0452 4.91455 17.1195 4.3709 17.1195C3.98674 17.1195 3.61524 17.0814 3.25099 17.0089C4.00844 19.3738 6.20481 21.0935 8.80709 21.1407C6.77202 22.7354 4.206 23.6831 1.41892 23.6831C0.938709 23.6831 0.465706 23.6541 0 23.6016C2.63308 25.2924 5.75906 26.2782 9.11879 26.2782C20.0624 26.2782 26.0444 17.2137 26.0444 9.35258L26.0244 8.58243C27.1932 7.74877 28.2044 6.70136 29 5.50894Z" fill="white"/>
                    </svg>
                </a>
                <a href="">
                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M28.764 5.35159H1.23598C0.55336 5.35159 0 5.90495 0 6.58757V23.4124C0 24.095 0.55336 24.6484 1.23598 24.6484H28.764C29.4466 24.6484 30 24.095 30 23.4124V6.58757C30 5.90495 29.4466 5.35159 28.764 5.35159ZM1.23598 6.32226H28.764C28.8802 6.32282 28.9824 6.39886 29.0164 6.50992L15 15.2653L0.983607 6.50992C1.01758 6.39886 1.11982 6.32282 1.23598 6.32226ZM0.970664 7.61648L11.0526 13.9129L0.970664 21.9111V7.61648ZM29.0293 23.4059C29.0293 23.5525 28.9105 23.6713 28.764 23.6713H1.23598C1.08949 23.6713 0.970664 23.5524 0.970664 23.4059V23.1471L11.9262 14.4888L14.7412 16.2489C14.8993 16.3487 15.1007 16.3487 15.2588 16.2489L18.0738 14.4888L29.0293 23.1471V23.4059ZM29.0293 21.9111L18.9215 13.9387L29.0293 7.67472V21.9111Z" fill="white"/>
                    </svg>
                </a>
            </div>
            <h2>Découvrez plus d'articles</h2>
            <div class="forward-posts-container">
                <div class="forward-posts-single" v-for="_post in forwardPosts" :key="_post.ID">
                    <img :src="_post.post_thumbnail_url" alt="">
                    <h3>{{_post.post_title}}</h3>
                    <p>{{_post.post_excerpt}}</p>
                    <a :href="_post.post_url">En savoir plus</a>
                </div>
            </div>
            <div class="link-container">
                <a class="all-posts" :href="archiveUrl">Voir tous nos articles</a>
            </div>
        </div>
        <Footer :footer-menu="footerMenu"></Footer>
    </div>
</template>

<script>
import Leaflet from 'leaflet'

import Header from './Header'
import Footer from './Footer'

export default {
    name: 'Home',
    components: {
        Header,
        Footer
    },
    data() {
        return {
            baseURL: window.baseURL,
            startingPointGeometry: {
                'lat': null,
                'long': null
                },
            endPointGeometry: {
                'lat': null,
                'long': null
            },
            covoitPlaces: null,
            elecPlaces: null,
            garesFrancePlaces: null,
            veloLibresPlaces: null,
            parkingVeloPlaces: null,
            garesIdfPlaces: null,
            map: null,
            covoitLayerGroup: null,
            elecLayerGroup: null,
            garesFranceLayerGroup: null,
            veloLibresLayerGroup: null,
            parkingVeloLayerGroup: null,
            garesIdfLayerGroup: null,
            startingPointLayerGroup: null,
            endPointLayerGroup: null,
            franceSelected: false,
            quotidienSelected: true,
            forwardPosts: null,
            allPosts: null,
            displayedPost: null,
            displayedInterval: null,
            autocompleteEndIsDisplayed: false
        }
    },
    props: {
        homeUrl: String,
        archiveUrl: String,
        covoit: String,
        elec: String,
        garesFrance: String,
        veloLibres: String,
        parkingVelo: String,
        garesIdf: String,
        forwardPostsString: String,
        allPostsString: String,
        footerMenu: String
    },
    methods: {
        replaceQuote(_string){
            return _string.replace('&rsquo;', '\'')
        },
        clearAllLayers(){
            this.parkingVeloLayerGroup.clearLayers()
            this.veloLibresLayerGroup.clearLayers()
            this.garesIdfLayerGroup.clearLayers()
            this.elecLayerGroup.clearLayers()
            this.covoitLayerGroup.clearLayers()
            this.garesFranceLayerGroup.clearLayers()
        },
        uncheckAll(){
            const radios = document.querySelectorAll('input[type="checkbox"]')
            for (const _radio of radios) {
                _radio.checked = false
            }
        },
        autocompleteStart(){
            const options = {
                componentRestrictions: {country: 'fr'}
            };
            const flagStart = new Leaflet.Icon({
                iconUrl: `${this.baseURL}/flag-start.png`,
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [46, 46],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            const autocompleteStart = new google.maps.places.Autocomplete(this.$refs.autocompleteStart, options);
            autocompleteStart.setFields(
            ['address_components', 'geometry', 'icon', 'name']);
            autocompleteStart.addListener('place_changed', ()=>{
                this.uncheckAll()
                this.clearAllLayers()
                let place = autocompleteStart.getPlace()
                this.startingPointGeometry.lat = place.geometry.location.lat()
                this.startingPointGeometry.long = place.geometry.location.lng()
                this.startingPointLayerGroup.clearLayers()
                Leaflet.marker([this.startingPointGeometry.lat, this.startingPointGeometry.long], {icon: flagStart, zIndexOffset: 101}).addTo(this.startingPointLayerGroup)
                if (!this.autocompleteEndIsDisplayed) {
                    window.setTimeout(() => {
                        this.autocompleteEnd()
                        this.autocompleteEndIsDisplayed = true
                    }, 500);
                }
            })
            
        },
        autocompleteEnd(){
            
            const options = {
                componentRestrictions: {country: 'fr'}
            };
            
            const flagEnd = new Leaflet.Icon({
                iconUrl: `${this.baseURL}/flag-end.png`,
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [46, 46],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            
            const autocompleteEnd = new google.maps.places.Autocomplete(this.$refs.autocompleteEnd, options);
            autocompleteEnd.setFields(
                ['address_components', 'geometry', 'icon', 'name']);
            autocompleteEnd.addListener('place_changed', ()=>{
                this.uncheckAll()
                this.clearAllLayers()
                let place = autocompleteEnd.getPlace()
                this.endPointGeometry.lat = place.geometry.location.lat()
                this.endPointGeometry.long = place.geometry.location.lng()
                this.endPointLayerGroup.clearLayers()
                Leaflet.marker([this.endPointGeometry.lat, this.endPointGeometry.long], {icon: flagEnd, zIndexOffset: 100}).addTo(this.endPointLayerGroup)
            })
        },
        getRandom(arr, n) {
            const result = new Array(n)
            let len = arr.length
            const taken = new Array(len);
            if (n > len)
                throw new RangeError("getRandom: more elements taken than available");
            while (n--) {
                const x = Math.floor(Math.random() * len);
                result[n] = arr[x in taken ? taken[x] : x];
                taken[x] = --len in taken ? taken[len] : len;
            }
            return result;
        },
        mapInit(){
            this.map = Leaflet.map('map').setView([48.85319519851149, 2.3305100714906075], 10);
            Leaflet.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
                attribution: 'Map data &copy; <a href="https://www.maps.google.com/">Google</a> and <a href="https://data.gouv.fr">data.gouv.fr</a>, Imagery © <a href="https://maps.google.com/">Google</a>',
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3']
            }).addTo(this.map)
            this.covoitLayerGroup = Leaflet.layerGroup().addTo(this.map)
            this.elecLayerGroup = Leaflet.layerGroup().addTo(this.map)
            this.garesFranceLayerGroup = Leaflet.layerGroup().addTo(this.map)
            this.veloLibresLayerGroup = Leaflet.layerGroup().addTo(this.map)
            this.parkingVeloLayerGroup = Leaflet.layerGroup().addTo(this.map)
            this.garesIdfLayerGroup = Leaflet.layerGroup().addTo(this.map)
            this.startingPointLayerGroup = Leaflet.layerGroup().addTo(this.map)
            this.endPointLayerGroup = Leaflet.layerGroup().addTo(this.map)
        },
        loadVeloLibres(_e){
            const greenIcon = new Leaflet.Icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            if (_e.target.checked) {
                for (const _place of this.veloLibresPlaces) {
                    let marker = Leaflet.marker([_place.geometry.coordinates[1], _place.geometry.coordinates[0]], {icon: greenIcon}).addTo(this.veloLibresLayerGroup)
                    marker.bindPopup(`<strong>${_place.fields.name}</strong>`)
                }
                window.clearInterval(this.displayedInterval)
                this.displayedPost = this.allPosts.filter(post => post.transport_type === 'velo')[0]
            }
            else{
                this.veloLibresLayerGroup.clearLayers()
                this.launchPreviewSlider()
            }
        },
        loadGaresIdf(_e){
            const redIcon = new Leaflet.Icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            if (_e.target.checked) {
                let newArray = this.garesIdfPlaces.filter(_place => {
                    return (
                        _place.geometry 
                        && (_place.geometry.coordinates[1] < this.startingPointGeometry.lat + 0.01 
                        && _place.geometry.coordinates[1] > this.startingPointGeometry.lat - 0.01
                        && _place.geometry.coordinates[0] > this.startingPointGeometry.long - 0.01
                        && _place.geometry.coordinates[0] < this.startingPointGeometry.long + 0.01)
                        || ( _place.geometry.coordinates[1] < this.endPointGeometry.lat + 0.01 
                        && _place.geometry.coordinates[1] > this.endPointGeometry.lat - 0.01
                        && _place.geometry.coordinates[0] > this.endPointGeometry.long - 0.01
                        && _place.geometry.coordinates[0] < this.endPointGeometry.long + 0.01)
                    )
                });
                for (const _place of newArray) {
                    let marker = Leaflet.marker([_place.geometry.coordinates[1], _place.geometry.coordinates[0]], {icon: redIcon}).addTo(this.garesIdfLayerGroup)
                    marker.bindPopup(`<strong>${_place.fields.nom_gare}</strong><br>${_place.fields.mode}<br>Ligne : ${_place.fields.ligne}`)
                }
                window.clearInterval(this.displayedInterval)
                this.displayedPost = this.allPosts.filter(post => post.transport_type === 'transports-en-commun')[0]
            }
            else{
                this.garesIdfLayerGroup.clearLayers()
                this.launchPreviewSlider()
            }
        },
        loadParkingVelo(_e){
            const blueIcon = new Leaflet.Icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            if (_e.target.checked) {
                let newArray = this.parkingVeloPlaces.filter(_place => {
                    return (
                        _place.geometry 
                        && ((_place.geometry.coordinates[1] < this.startingPointGeometry.lat + 0.01 
                        && _place.geometry.coordinates[1] > this.startingPointGeometry.lat - 0.01
                        && _place.geometry.coordinates[0] > this.startingPointGeometry.long - 0.01
                        && _place.geometry.coordinates[0] < this.startingPointGeometry.long + 0.01)
                        || ( _place.geometry.coordinates[1] < this.endPointGeometry.lat + 0.01 
                        && _place.geometry.coordinates[1] > this.endPointGeometry.lat - 0.01
                        && _place.geometry.coordinates[0] > this.endPointGeometry.long - 0.01
                        && _place.geometry.coordinates[0] < this.endPointGeometry.long + 0.01))
                    )
                });
                for (const _place of newArray) {
                    let marker = Leaflet.marker([_place.geometry.coordinates[1], _place.geometry.coordinates[0]], {icon: blueIcon}).addTo(this.parkingVeloLayerGroup)
                    marker.bindPopup(`<strong>Couvert : </strong>${_place.fields.couvert}<br><strong>Payant : </strong>${_place.fields.payant}`)
                }
                window.clearInterval(this.displayedInterval)
                this.displayedPost = this.allPosts.filter(post => post.transport_type === 'velo')[0]
            }
            else{
                this.parkingVeloLayerGroup.clearLayers()
                this.launchPreviewSlider()
            }
        },
        loadCovoit(_e){
            const greenIcon = new Leaflet.Icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            if (_e.target.checked) {
                let newArray = this.covoitPlaces.filter(_place => {
                    if(_place){
                        
                        return (
                            (_place['Latitude'] < this.startingPointGeometry.lat + 0.08 
                            && _place['Latitude'] > this.startingPointGeometry.lat - 0.08
                            && _place['Longitude'] > this.startingPointGeometry.long - 0.08
                            && _place['Longitude'] < this.startingPointGeometry.long + 0.08)
                            || (_place['Latitude'] < this.endPointGeometry.lat + 0.08 
                            && _place['Latitude'] > this.endPointGeometry.lat - 0.08
                            && _place['Longitude'] > this.endPointGeometry.long - 0.08
                            && _place['Longitude'] < this.endPointGeometry.long + 0.08)
                        )
                    }
                    
                });
                
                for (const _place of newArray) {
                    let marker = Leaflet.marker([_place['Latitude'], _place['Longitude']], {icon: greenIcon}).addTo(this.covoitLayerGroup)
                    marker.bindPopup(`<strong>${_place['Ville']}</strong><br>${_place['Nom du lieu']}`)
                }
                
                window.clearInterval(this.displayedInterval)
                this.displayedPost = this.allPosts.filter(post => post.transport_type === 'covoiturage')[0]
            }
            else{
                this.covoitLayerGroup.clearLayers()
                this.launchPreviewSlider()
            }
        },
        loadElec(_e){
            const blueIcon = new Leaflet.Icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            
            let newArray = this.elecPlaces.filter(_place => {
                    if(_place){
                        
                        return (
                            (_place['Ylatitude'] < this.startingPointGeometry.lat + 0.1 
                            && _place['Ylatitude'] > this.startingPointGeometry.lat - 0.1
                            && _place['Xlongitude'] > this.startingPointGeometry.long - 0.1
                            && _place['Xlongitude'] < this.startingPointGeometry.long + 0.1)
                            || (_place['Ylatitude'] < this.endPointGeometry.lat + 0.1 
                            && _place['Ylatitude'] > this.endPointGeometry.lat - 0.1
                            && _place['Xlongitude'] > this.endPointGeometry.long - 0.1
                            && _place['Xlongitude'] < this.endPointGeometry.long + 0.1)
                        )
                    }
                    
                });
            if (_e.target.checked) {
                for (const _place of newArray) {
                    try {
                        let marker = Leaflet.marker([_place['Ylatitude'], _place['Xlongitude']], {icon: blueIcon}).addTo(this.elecLayerGroup)
                        marker.bindPopup(`<strong>${_place['n_station']}</strong><br>${_place['n_amenageur']}`)
                    } catch (error) {
                        console.log(error);
                        
                    }
                }
                
                window.clearInterval(this.displayedInterval)
                this.displayedPost = this.allPosts.filter(post => post.transport_type === 'voiture-electrique')[0]
            }
            else{
                this.elecLayerGroup.clearLayers()
                this.launchPreviewSlider()
            }
        },
        loadGaresFrance(_e){
            const redIcon = new Leaflet.Icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            if (_e.target.checked) {
                let newArray = this.garesFrancePlaces.filter(_place => {
                    return (
                        _place.geometry 
                        && ((_place.geometry.coordinates[1] < this.startingPointGeometry.lat + 0.1 
                        && _place.geometry.coordinates[1] > this.startingPointGeometry.lat - 0.1
                        && _place.geometry.coordinates[0] > this.startingPointGeometry.long - 0.1
                        && _place.geometry.coordinates[0] < this.startingPointGeometry.long + 0.1)
                        || (_place.geometry.coordinates[1] < this.endPointGeometry.lat + 0.1 
                        && _place.geometry.coordinates[1] > this.endPointGeometry.lat - 0.1
                        && _place.geometry.coordinates[0] > this.endPointGeometry.long - 0.1
                        && _place.geometry.coordinates[0] < this.endPointGeometry.long + 0.1))
                    )
                });
                for (const _place of newArray) {
                    if (_place.geometry) {
                        let marker = Leaflet.marker([_place.geometry.coordinates[1], _place.geometry.coordinates[0]], {icon: redIcon}).addTo(this.garesFranceLayerGroup)
                        marker.bindPopup(`<strong>${_place.fields.commune}</strong>`)
                        
                    }
                }
                window.clearInterval(this.displayedInterval)
                this.displayedPost = this.allPosts.filter(post => post.transport_type === 'train')[0]
            }
            else{
                this.garesFranceLayerGroup.clearLayers()
                this.launchPreviewSlider()
            }
        },
        changeServiceType(){
            const radios = document.querySelectorAll('input[type="checkbox"]')
            for (const _radio of radios) {
                _radio.checked = false
            }
            this.franceSelected = !this.franceSelected
            this.quotidienSelected = !this.quotidienSelected
            this.parkingVeloLayerGroup.clearLayers()
            this.veloLibresLayerGroup.clearLayers()
            this.garesIdfLayerGroup.clearLayers()
            this.elecLayerGroup.clearLayers()
            this.covoitLayerGroup.clearLayers()
            this.garesFranceLayerGroup.clearLayers()
            window.clearInterval(this.displayedInterval)
            if (this.franceSelected) {
                this.map.setView([47, 3], 5.5)
                this.displayedPost = this.allPosts.filter(_post => (_post.transport_type === 'voiture-electrique' || _post.transport_type == 'train' || _post.transport_type == 'covoiturage'))[0]
                this.launchPreviewSlider()
            }
            if (this.quotidienSelected) {
                this.displayedPost = this.allPosts.filter(_post => (_post.transport_type === 'velo'|| _post.transport_type === 'transports-en-commun'))[0]
                this.launchPreviewSlider()
                this.map.setView([48.85319519851149, 2.3305100714906075], 10)
            }
        },
        launchPreviewSlider(){
            if (this.quotidienSelected) {
                this.displayedInterval = window.setInterval(() => {
                    const potentialPosts = this.allPosts.filter(_post => (_post != this.displayedPost && (_post.transport_type === 'velo' || _post.transport_type == 'transports-en-commun')))
                    if (potentialPosts.length > 0) {
                        this.displayedPost = potentialPosts[Math.floor(Math.random() * (potentialPosts.length - 1))]
                    }
                }, 10000);
            }
            else if(this.franceSelected){
                this.displayedInterval = window.setInterval(() => {
                    const potentialPosts = this.allPosts.filter(_post => (_post != this.displayedPost && (_post.transport_type === 'voiture-electrique' || _post.transport_type == 'train' || _post.transport_type == 'covoiturage')))
                    if (potentialPosts.length > 0) {
                        this.displayedPost = potentialPosts[Math.floor(Math.random() * (potentialPosts.length - 1))]
                    }
                }, 10000);
            }
        }
    },
    beforeMount() {
        this.covoitPlaces = JSON.parse(this.covoit)
        this.elecPlaces = JSON.parse(this.elec)
        this.garesFrancePlaces = JSON.parse(this.garesFrance)
        this.veloLibresPlaces = JSON.parse(this.veloLibres)
        this.parkingVeloPlaces = JSON.parse(this.parkingVelo)
        this.garesIdfPlaces = JSON.parse(this.garesIdf)
        this.forwardPosts = JSON.parse(this.forwardPostsString)
        this.allPosts = JSON.parse(this.allPostsString)
        this.displayedPost = this.allPosts.filter(_post => (_post.transport_type === 'velo'|| _post.transport_type === 'transports-en-commun'))[0]
        },
    mounted() {
        this.mapInit()
        this.autocompleteStart()
        this.launchPreviewSlider()
        
    }
}
</script>

<style lang="scss" scoped>

    .indicator{
        font-family: var(--cursive-font);
        font-size: 1.8rem;
        position: relative;
        display: inline-block;
        padding-bottom: 35px;
        @media screen and (max-width: 800px) {
            display: none;
        }
        &::after{
            content: '';
            position: absolute;
            bottom: 0;
            width: 30px;
            height: 30px;
            background-image: url(../imgs/arrow-round.png);
        }
        &.indicator-1{
            left: 20%;
            margin-bottom: 0;
            &::after{
                left: 40%;
                transform: rotate(25deg);
            }
        }
        &.indicator-2{
            left: -8%;
            margin-bottom: 1rem;
            &::after{
                transform: rotate(30deg);
                left: 20%;
            }
        }
        &.indicator-3{
            margin-bottom: 1rem;
            left: 50%;
            &::after{
                left: 75%;
                transform: scaleX(-1) rotate(30deg);
            }
        }
    }

    .home{
        margin: 0 auto;
        max-width: 120rem;
        margin-bottom: 7rem;
        @media screen and (max-width: 800px){
            padding: 0 2rem;
        }
    }
    #map{
        width: 550px;
        box-shadow: var(--shadow);
        border-radius: 4px;
        @media screen and (max-width: 800px) {
            width: 100%;
            height: 400px;
        }
    }
    .transport-types{
        display: flex;
        align-items: flex-end;
        justify-content: center;
        @media screen and (max-width: 800px) {
            flex-direction: column;
            align-items: center;
        }
        h2{
            padding: 1rem;
            border-radius: 50px;
            text-align: center;
            font-size: 1.8rem;
            cursor: pointer;
            label{
                width: 100%;
                cursor: pointer;
            }
            &:first-of-type{
                margin-right: 2rem;
            }
            @media screen and (max-width: 800px) {
                margin-bottom: 1rem;
                margin-right: 0;
            }
        }
        input[type="radio"]{
            display: none;
            &:checked+h2{
                background: var(--green);
                color: white;
                font-size: 2.2rem;
            }
        }
    }
    
    .tool{
        display: flex;
        justify-content: space-between;
        max-width: 120rem;
        margin: 0 auto;
        margin-bottom: 3rem;
        
        .field{
            label{
                font-weight: bold;
                width: 100%;
            }
            input{
                display: block;
                width: 100%;
                border-radius: 4px;
                border: none;
                box-shadow: none;
                background-color: var(--light-blue);
                padding: 1rem;
                color: white;
                &:focus{
                    outline-color: white;
                }
                &::placeholder{
                    color: white;
                    opacity: 0.5;
                } 
            }
            margin-bottom: 1.5rem;
        }
        .sidebar-left{
            width: 300px;
            background-color: var(--dark-blue);
            padding: 2rem 1.5rem;
            border-radius: 4px; 
            color: white;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            justify-content: center;
            &.flex-start{
                justify-content: flex-start;
            }
            h3{
                margin: 0;
                margin-bottom: 0.5rem;
                font-size: 1.8rem;
            }
            .locations{
                margin-bottom: 3rem;
            }
            .filters{
                margin-top: 2rem;
                input[type="checkbox"]{
                    display: none;
                    &:checked+label{
                        &::before{
                            background-image: url(../imgs/check.png);
                            background-repeat: no-repeat;
                            background-position: center;
                        }
                    }
                }
                label{
                    display: block;
                    margin-bottom: 2rem;
                    padding-left: 35px;
                    position: relative;
                    cursor: pointer;
                    &::before{
                        content: '';
                        position: absolute;
                        top: 50%;
                        left: 0;
                        transform: translateY(-50%);
                        width: 20px;
                        height: 20px;
                        background-color: white;
                        border-radius: 2px;
                    }
                }
            }
        }
        .sidebar-right{
            max-width: 300px;
            color: white;
            @media screen and (max-width: 800px) {
                order: 3;
            }
            .article-preview{
                background-color: var(--light-blue);
                border-radius: 4px;
                padding: 2rem 1.5rem;
                margin-bottom: 1.5rem;
                box-shadow: var(--shadow);
                * {
                    transition: opacity 0.2s;
                }
                &.fading{
                    *{
                        opacity: 0;
                    }
                }
                h3{
                    margin-top: 0;
                    margin-bottom: 1.5rem;
                }
                img{
                    display: block;
                    width: 100%;
                    max-height: 200px;
                    object-fit: cover;
                    margin-bottom: 2rem;
                }
                a{
                    color: inherit;
                    font-weight: bold;
                    text-decoration: none;
                    position: relative;
                    &::after{
                        content: '';
                        position: absolute;
                        right: -22px;
                        top: 50%;
                        transform: translateY(-50%);
                        width: 16px;
                        height: 16px;
                        background: url(../imgs/arrow.png) no-repeat;
                        background-position: center;
                        transition: transform 0.2s;
                    }
                    &:hover::after{
                        transform: translateY(-50%) translateX(5px);
                    }
                }
            }
            .dl-kml{
                background-color: var(--red);
                border-radius: 4px;
                padding: 2rem 1.5rem;
                box-shadow: var(--shadow);
                p{
                    font-size: 1.4rem;
                    
                }
                button{
                    display: block;
                    border: none;
                    border-radius: 4px;
                    background-color: grey;
                    box-shadow: none;
                    color: black;
                    font-family: inherit;
                    padding: 1rem;
                    margin-bottom: 1rem;
                    cursor: pointer;
                }
                a{
                    color: inherit;
                    font-size: 1.2rem;
                    cursor: help;
                }
            }
        }
        @media screen and (max-width: 800px){
            flex-direction: column;
            &>*{
                width: 100%!important;
                max-width: 100%!important;
            }
        }
    }
    
    .title{
        text-align: center;
        margin-bottom: 4rem;
        p{
            font-style: italic;
        }
    }
    
    .forward-posts-container{
        display: flex;
        justify-content: space-between;
        margin-bottom: 5rem;
        @media screen and (max-width: 800px){
            flex-direction: column;
        }
        .forward-posts-single{
            width: 30%;
            padding: 1.5rem 2rem;
            background: var(--dark-blue);
            color: white;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow);
            position: relative;
            @media screen and (max-width: 800px){
                width: 100%;
                margin-bottom: 3rem;
            }
            img{
                width: 100%;
            }
            p{
                font-size: 1.5rem;
                margin-bottom: 5rem;
            }
            a{
                position: absolute;
                bottom: 1.5rem;
                right: 2rem;
                display: inline-block;
                background: white;
                font-weight: bold;
                padding: 1rem;
                border-radius: 50px;
                color: black;
                padding-right: 30px;
                text-decoration: none;
                margin-left: auto;
                font-size: 1.4rem;
                transition: transform 0.2s;
                &:hover{
                    box-shadow: var(--shadow);
                    transform: translateY(-5px);
                }
                &:active{
                    transform: translateY(0);
                    box-shadow: none;
                }
                &::after{
                    content: '';
                    background-image: url(../imgs/arrow-blue.png);
                    width: 16px;
                    height: 16px;
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    right: 10px;
                }
            }
        }
    }
    .share{
        margin-bottom: 6rem;
        a{
            display: inline-flex;
            margin-right: 1rem;
            justify-content: center;
            align-items: center;
            border-radius: 100%;
            padding: 1.3rem;
            background-color: var(--dark-blue);
            svg{
                width: 16px;
                height: 16px;
            }
        }
    }
    
    .link-container{
        text-align: center;
    }
    
    .all-posts{
        display: inline-block;
        margin: 0 auto;
        padding: 1.5rem;
        background-color: var(--green);
        color: white;
        border-radius: 50px;
        text-decoration: none;
        transition: transform 0.2s;
        &:hover{
            box-shadow: var(--shadow);
            transform: translateY(-5px);
        }
        &:active{
            transform: translateY(0);
            box-shadow: none;
        }
    }
</style>
