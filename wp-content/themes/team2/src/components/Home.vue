<template>
    <div class="home">
        <div class="title">
            <h1>Prêts à découvrir des alternatives à la voiture et à l'avion ?</h1>
            <p>
                Grâce à <strong>Feuille de Route</strong> vous pouvez découvrir toutes les informations sur le developpement durable et les transports, et choisir la meilleur alternative aux transports polluants pour votre trajet !
            </p>
        </div>
        <div class="transport-types">
            <input name="serviceType" type="radio" @change="changeServiceType" id="quotidien" checked>
            <h2><label for="quotidien">Trajets du quotidien en Ile de France</label></h2>
            <input name="serviceType" type="radio" @change="changeServiceType" id="france">
            <h2><label for="france">Trajets sur la France</label></h2>
        </div>
        <div class="tool">
            <div class="sidebar-left">
                <div class="locations">
                    <div class="field">
                        <h3><label for="depart">Mon point de départ</label></h3>
                        <input placeholder="Exemple : Place de la Nation" type="text" name="depart" id="depart" ref="autocompleteStart">
                    </div>
                    <div class="field">
                        <h3><label for="arrivee">Mon point d'arrivée</label></h3>
                        <input placeholder="Exemple : Rue du Faubourg Saint-Honoré" type="text" name="arrivee" id="arrivee" ref="autocompleteEnd">
                    </div>
                </div>
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
                <p>Rien ne s'affiche ? Essayez de chosir un point de départ et de destination !</p>
            </div>
            <div id="map"></div>
            <div class="sidebar-right">
                <div class="article-preview" ref="preview">
                    <h3>{{displayedPost.title}}</h3>
                    <p>{{displayedPost.incent}}</p>
                    <img :src="displayedPost.thumbnail_url" alt="">
                    <a :href="displayedPost.permalink">En savoir plus</a>
                </div>
                <div class="dl-kml">
                    <p>Téléchargez notre fichier KML pour l'ajouter à votre application de plan préférée !</p>
                    <button>Télécharger</button>
                    <a href="">Comment l'ajouter ?</a>
                </div>
            </div>
        </div>
        <h2>Une sélection d'articles</h2>
        <div class="forward-posts-container">
            <div class="forward-posts-single" v-for="_post in forwardPosts" :key="_post.ID">
                <img :src="_post.post_thumbnail_url" alt="">
                <h3>{{_post.post_title}}</h3>
                <p>{{_post.post_excerpt}}</p>
                <a :href="_post.post_url">En savoir plus</a>
            </div>
        </div>
    </div>
</template>

<script>
import Leaflet from 'leaflet'

export default {
    name: 'Home',
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
            displayedInterval: null
        }
    },
    props: {
        covoit: String,
        elec: String,
        garesFrance: String,
        veloLibres: String,
        parkingVelo: String,
        garesIdf: String,
        forwardPostsString: String,
        allPostsString: String
    },
    methods: {
        autocomplete(){
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
            const flagEnd = new Leaflet.Icon({
                iconUrl: `${this.baseURL}/flag-end.png`,
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
                let place = autocompleteStart.getPlace()
                this.startingPointGeometry.lat = place.geometry.location.lat()
                this.startingPointGeometry.long = place.geometry.location.lng()
                this.startingPointLayerGroup.clearLayers()
                Leaflet.marker([this.startingPointGeometry.lat, this.startingPointGeometry.long], {icon: flagStart, zIndexOffset: 101}).addTo(this.startingPointLayerGroup)
            })
            const autocompleteEnd = new google.maps.places.Autocomplete(this.$refs.autocompleteEnd, options);
            autocompleteEnd.setFields(
                ['address_components', 'geometry', 'icon', 'name']);
            autocompleteEnd.addListener('place_changed', ()=>{
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
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://maps.google.com/">Google</a>',
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
                            (_place['Latitude'] < this.startingPointGeometry.lat + 0.1 
                            && _place['Latitude'] > this.startingPointGeometry.lat - 0.1
                            && _place['Longitude'] > this.startingPointGeometry.long - 0.1
                            && _place['Longitude'] < this.startingPointGeometry.long + 0.1)
                            || (_place['Latitude'] < this.endPointGeometry.lat + 0.1 
                            && _place['Latitude'] > this.endPointGeometry.lat - 0.1
                            && _place['Longitude'] > this.endPointGeometry.long - 0.1
                            && _place['Longitude'] < this.endPointGeometry.long + 0.1)
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
            if (_e.target.checked) {
                for (const _place of this.elecPlaces) {
                    let marker = Leaflet.marker([_place['Ylatitude'], _place['Xlongitude']], {icon: blueIcon}).addTo(this.elecLayerGroup)
                    marker.bindPopup(`<strong>${_place['n_station']}</strong><br>${_place['n_amenageur']}`)
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
        this.autocomplete()
        this.launchPreviewSlider()
        console.log(this.forwardPosts);
        
    }
}
</script>

<style lang="scss" scoped>

    .home{
        margin: 0 auto;
        max-width: 120rem;
    }
    #map{
        width: 550px;
        box-shadow: var(--shadow);
        border-radius: 4px;
    }
    .transport-types{
        display: flex;
        align-items: flex-end;
        justify-content: center;
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
        margin-bottom: 6rem;
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
                    background-color: white;
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
    }
    
    .title{
        text-align: center;
        p{
            font-style: italic;
        }
    }
    
    .forward-posts-container{
        display: flex;
        justify-content: space-between;
        .forward-posts-single{
            width: 30%;
            padding: 1.5rem 2rem;
            background: var(--dark-blue);
            color: white;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow);
            img{
                width: 100%;
            }
            p{
                font-size: 1.5rem;
                margin-bottom: 2rem;
            }
            a{
                display: inline-block;
                background: white;
                font-weight: bold;
                padding: 1rem;
                border-radius: 50px;
                color: black;
                padding-right: 30px;
                position: relative;
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
</style>
