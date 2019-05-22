<template>
    <div class="composer">
        <textarea v-model="message" @keydown.enter="send" placeholder="Message ..... "></textarea>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                message: ''
            }
        },
        methods:{
            send(e){
                e.preventDefault();
                if(this.message === ''){
                    return;
                }
                this.$emit('send', this.message);
                this.message = '';
            }
        }
    }
</script>

<style scoped lang="scss">
    .composer {
        textarea{
            resize: none;
            width: 96%;
            margin: 10px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #aaa;
        }
    }
</style>