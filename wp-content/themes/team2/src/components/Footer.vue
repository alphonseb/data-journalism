<template>
    <footer>
        <div class="newsletter">
            <div class="container">
                <h4>Newsletter</h4>
                <input type="email" placeholder="Entrez votre addresse e-mail...">
                <button>Je m'inscris</button>
            </div>
        </div>
        <div class="links">
            <div class="container">
                <div class="socials">
                    <h4>Suivez nous</h4>
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
                </div>
                <nav>
                    <ul>
                        <li v-for="_menuItem in menu" :key="_menuItem.id">
                            <a :href="_menuItem.url">{{_menuItem.title}}</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'Footer',
    props: {
        footerMenu: String
    },
    data() {
        return {
            menu: null
        }
    },
    beforeMount() {
        this.menu = JSON.parse(this.footerMenu)
    }
}
</script>

<style lang="scss" scoped>
.newsletter{
    padding: 1.5rem 0;
    color: white;
    background-color: #ACCEDC;
    .container{
        display: flex;
        align-items: center;
        max-width: 120rem;
        margin: 0 auto;
    }
    h4{
        margin-right: 2rem;
    }
    
    input{
        padding: 1rem;
        margin-right: 1rem;
        background-color: #fff;
        border-radius: 4px;
        border: none;
        box-shadow: none;
        min-width: 250px;
        @media screen and (max-width: 420px){
            margin-bottom: 1rem;
        }
    }
    button{
        border: none;
        box-shadow: none;
        background-color: var(--light-blue);
        font: inherit;
        color: white;
        font-weight: bold;
        font-size: 1.4rem;
        border-radius: 4px;
        padding: 1rem;
        cursor: pointer;
    }
    @media screen and (max-width: 800px) {
        .container{
            padding: 0 2rem;
            flex-wrap: wrap;
            h4{
                width: 100%;
            }
        }
    }
}
.links{
    background-color: var(--dark-blue);
    color: white;
    padding: 3rem 0;
    .container{
        max-width: 120rem;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        
        .socials{
            display: flex;
            align-items: center;
            h4{
                margin-right: 2rem;
            }
            a{
                margin-right: 1rem;
            }
        }
        nav{
            ul{
                display: flex;
                padding-left: 0;
                li{
                    list-style: none;
                    margin-left: 3rem;
                    a{
                        color: white;
                        font-weight: bold;
                        text-decoration: none;
                        font-size: 1.5rem;
                    }
                }
            }
        }
        @media screen and (max-width: 800px) {
            flex-wrap: wrap;
            .socials{
                width: 100%;
                justify-content: center;
            }
        }
    }
}
</style>


