<template>
    <button  class="btn  btn-default col-xs-4 col-xs-offset-1" @click="vote"
             :class="{ 'btn-primary': voted }">
        {{ count }}
    </button>
</template>

<script>
    export default {
        props: ['answer', 'count'],
        mounted() {
            this.$http.get('/api/answer/' + this.answer + '/votes/users').then(response => {
                this.voted = response.data.voted;
        })
        },
        data() {
            return {
                voted: false
            }
        },
        methods: {
            vote() {
                this.$http.post('/api/answer/vote',{'answer': this.answer}).then(response => {
                    this.voted = response.data.voted;
                    this.voted ? this.count ++ : this.count --;
            })
            }
        }
    }
</script>

<style>
</style>