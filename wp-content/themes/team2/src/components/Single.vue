<template>
<div>
    <div class="single">
        <Header :home-url="homeUrl"></Header>
        <a class="home-link" :href="homeUrl">Revenir à l'accueil</a>
        <h1>{{ title }}</h1>
        <p>
            {{excerpt}}
        </p>
        <img :src="thumbnail_url" alt="">
        <div class="vignettes-container">
            <div v-for="_vignette in vignettes" :key="_vignette.id" :class="_vignette.type" class="vignette-single">
                <div class="front">
                    <p v-if="_vignette.type==='number'|| _vignette.type==='word'">{{_vignette.front}}</p>
                    <img v-if="_vignette.type==='graph_large'||_vignette.type==='graph_small'||_vignette.type==='photo'" :src="_vignette.front['url']" alt="">
                </div>
                <div class="back" v-html="_vignette.back"></div>
            </div>
        </div>
    </div>
    <Footer :footer-menu="footerMenu"></Footer>
</div>
</template>

<script>
import Header from './Header'
import Footer from './Footer'

export default {
    name: 'Single',
    props: {
        title: String,
        excerpt: String,
        thumbnail_url: String,
        vignettes_string: String,
        homeUrl: String,
        footerMenu: String
    },
    components: {
        Header,
        Footer
    },
    data() {
        return {
            vignettes: null
        }
    },
    beforeMount() {
        this.vignettes = JSON.parse(this.vignettes_string)
    },
    mounted() {
        console.log(this.vignettes)
    },
}
</script>

<style lang="scss" scoped>
.single{
    perspective: 800px;
    max-width: 120rem;
    margin: 0 auto;
    margin-bottom: 6rem;
}

.home-link{
    display: inline-block;
    background: var(--dark-blue);
    color: white;
    padding: 1.2rem;
    text-decoration: none;
    padding-left: 30px;
    position: relative;
    font-size: 1.4rem;
    border-radius: 50px;
    transition: transform 0.2s;
    &:hover{
        box-shadow: var(--shadow);
        transform: translateY(-5px);
    }
    &:active{
        transform: translateY(0);
        box-shadow: none;
    }
    &::before{
        content: '';
        position: absolute;
        transform: translateY(-50%);
        top: 50%;
        left: 10px;
        width: 16px;
        height: 16px;
        background-image: url(../imgs/arrow-white.png);
        background-repeat: no-repeat;
        background-size: contain; 
    } 
}

.vignettes-container { 
    max-width: 120rem;
    margin: 0 auto;
    display: grid; 
    grid-template-columns: 2.5fr 1.5fr 1.5fr 1fr; 
    grid-template-rows: 2fr 1.5fr; 
    grid-column-gap: 20px;
    grid-row-gap: 20px; 
    .graph_large { grid-area: 1 / 1 / 3 / 2; } 
    .number { grid-area: 1 / 2 / 2 / 3; } 
    .graph_small { grid-area: 1 / 3 / 2 / 4; } 
    .word { grid-area: 2 / 2 / 3 / 4; } 
    .photo { grid-area: 1 / 4 / 3 / 5; } 
}

.vignette-single{
    font-weight: bold;
    position: relative;
    transform-style: preserve-3d;
    display: flex;
    justify-content: center;
    align-items: center;
    .front, .back{
        border: solid 2px var(--dark-blue);
        border-radius: 4px;
        padding: 1rem;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .front{
        backface-visibility: hidden;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        p{
            margin: 0;
        }
        img{
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }
    .back{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotateY(-180deg);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        backface-visibility: hidden;
        color: var(--dark-blue);
        height: 100%;
        overflow-y: auto;
        display: block;
        align-items: flex-start;
        p{
            margin: 0;
        }
    }
    &:hover{
        .front{
            transform: rotateY(180deg); 
        }
        .back{
            transform: translate(-50%, -50%) rotateY(0);
        }
    }
}

.number, .word, .photo{
    .front, .back{
        color: white;
        background: var(--dark-blue);
    }
}

.graph_large, .graph_small, .photo{
    .front{
        padding: 0;
    }
}

.graph_large .front img{
    object-fit: contain;
}

.number .front{
    font-size: 6rem;
    p{
        margin: 0;
        text-align: center;
    }
}

.word .front{
    font-size: 4rem;
}
</style>

