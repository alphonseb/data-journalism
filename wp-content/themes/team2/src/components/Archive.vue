<template>
<div>
    <div class="archive">
        <Header :home-url="homeUrl"></Header>
        <div class="posts-container">
            <div class="single-post" v-for="_post in allPosts" :key="_post.id">
                <img :src="_post.thumbnail_url" alt="">
                <h2>{{replaceApostrophe(_post.title)}}</h2>
                <p>{{_post.excerpt}}</p>
                <a :href="_post.permalink">En savoir plus</a>
            </div>
        </div>
    </div>
    <Footer :footer-menu="footerMenu"></Footer>
</div>
</template>

<script>
import Header from './Header.vue'
import Footer from './Footer.vue'

export default {
    name: 'Archive',
    props:{
        allPostsString: String,
        homeUrl: String,
        footerMenu: String
    },
    components: {
        Header,
        Footer
    },
    data() {
        return {
            allPosts: null
        }
    },
    methods: {
        replaceApostrophe(_string){
            return _string.replace('&rsquo;', '\'')
        }
    },
    beforeMount() {
        this.allPosts = JSON.parse(this.allPostsString)
    },
    mounted() {
        console.log(this.allPosts);
        
    },
}
</script>

<style lang="scss" scoped>
    .archive{
        max-width: 120rem;
        margin: 0 auto;
        @media screen and (max-width: 800px) {
            padding: 0 2rem;
        }
    }
    
    .posts-container{
        margin-bottom: 3rem;
        display: flex;
        flex-wrap: wrap;
        .single-post{
            margin-right: calc(10% / 2);
            width: 30%;
            padding: 1.5rem 2rem;
            background: var(--dark-blue);
            color: white;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow);
            position: relative;
            margin-bottom: 6rem;
            &:nth-child(3n){
                margin-right: 0;
            }
            @media screen and (max-width: 800px){
                width: 100%;
                margin-right: 0;
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
</style>
