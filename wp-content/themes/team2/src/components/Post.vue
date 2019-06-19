<template>
    <div>
        <h2>{{title}}</h2>
        <small>{{id}}</small>
    </div>
</template>

<script>
export default {
    name: 'Post',
    props: {
        title: String,
        id: Number
    }
}
</script>

