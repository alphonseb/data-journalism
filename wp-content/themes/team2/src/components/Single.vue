<template>
    <div class="single">
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
</template>

<script>
export default {
    name: 'Single',
    props: {
        title: String,
        excerpt: String,
        thumbnail_url: String,
        vignettes_string: String
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
        border: solid 2px black;
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
        background: black;
    }
}
</style>

