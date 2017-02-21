<template>
    <button  class="btn  btn-default col-md-4 col-md-offset-1" @click="follow"
             :class="{ 'btn-success': followed }">
        {{ text }}
    </button>
</template>

<script>
    export default {
        props: ['question'],
        mounted() {

                this.$http.post('/api/question/follower',{'question': this.question}).then(response => {
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
                return this.followed ? '取消关注' : '关注问题';
            }
        },
        methods: {
            follow() {
                this.$http.post('/api/question/follow',{'question': this.question}).then(response => {
                    this.followed = response.data.followed;
                })
            }
        }
    }
</script>

<style>
</style>