<template>
    <div class="conversation">
        <h1>{{ contact ? contact.name : 'Select a user' }}</h1>
        <MessagesFeed :contact="contact" :messages="messages" />
        <MessageComposer @send="sendMessage" />
    </div>
</template>

<script>
    import MessagesFeed from './MessagesFeed'
    import MessageComposer from './MessageComposer'

    export default {
        components:{MessagesFeed, MessageComposer},
        props:{
            contact:{
                type: Object,
                default: null
            },
            messages:{
                type: Array,
                default: []
            }
        },
        methods:{
            sendMessage(text) {
                axios.post(`${url}/conversation/send`, {
                    contact_id: this.contact.id,
                    text: text
                }).then((res) => {
                    this.$emit('newMessage', res.data);
                })
            }
        }
    }
</script>
<style scoped lang="scss">
    .conversation{
        flex:5;
        display: flex;
        justify-content: space-between;
        flex-direction: column;

        h1{
            font-size: 20px;
            padding: 10px;
            margin:0;
            border-bottom: 1px dashed #aaaaaa;
        }
    }
</style>