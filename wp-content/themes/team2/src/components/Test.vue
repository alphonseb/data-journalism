<template>
    <div>
        <input type="text" v-model="realmsg">
        <h1>{{realmsg}}</h1>
        <button @click="count++">Increment</button>
        <p>{{ count }}</p>
    </div>
</template>

<script>
export default {
    name: 'Test',
    props: {
        msg: String
    },
    data() {
        return {
            realmsg: '',
            count: 0
        }
    },
    mounted() {
        this.realmsg = this.msg
    },
    
}
</script>

<style scoped>
    input{
        background: blue;
    }
</style>
