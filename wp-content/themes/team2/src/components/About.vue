<template>
    <div class="about">
        <Hello> </Hello>
        <h1>{{content.title}}</h1>
        <div v-html="content.content"> </div>
        <Post v-for="(_post, key) in content.posts" :key="key" :title="_post.title" :id="_post.id"> </Post>
    </div>
</template>

<script>
import Post from './Post.vue'
import Hello from './Hello.vue'

export default {
    name: 'About',
    components: {
        Post,
        Hello
    },
    data() {
        return {
            content: null
        }
    },
    props: {
        data: String
    },
    beforeMount() {
        this.content = JSON.parse(this.data)
    },
    mounted() {
        console.log(this.content);
        
    },
}
</script>

<style lang="scss" scoped>
    h1 {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }
</style>


