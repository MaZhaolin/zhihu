<template>
    <button  class="btn  btn-default col-xs-4 col-xs-offset-1" @click="follow"
             :class="{ 'btn-success': followed }">
        {{ text }}
    </button>
</template>

<script>
    export default {
        props: ['user'],
        mounted() {
            this.$http.get('/api/user/' + this.user + '/follower').then(response => {
                this.followed = response.data.followed;
        })
        },
        data() {
            return {
                followed: false
            }
        },
        computed: {
            text() {
                return this.followed ? '取消关注' : '关注他';
            }
        },
        methods: {
            follow() {
                this.$http.post('/api/user/follow',{'user': this.user}).then(response => {
                    this.followed = response.data.followed;
            })
            }
        }
    }
</script>

<style>
</style>