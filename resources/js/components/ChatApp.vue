<template>
    <div class="chat-app">
        <Conversation :contact="selectedContact" :messages="messages" @newMessage="receivedNewMessage"/>
        <ContactList :contacts="contacts" @selected="startConversationWith"/>
    </div>
</template>

<script>
    import Conversation from './Conversation.vue'
    import ContactList from './ContactList.vue'
    import Pusher from 'pusher-js'


    export default {
        props:{
          user:{
              type:Object,
              required:true
          }
        },
        data(){
          return {
              selectedContact:null,
              contacts:[],
              messages:[]
          }
        },
        mounted() {
            let pusher = this.initPusher();
            let channel = pusher.subscribe(`messages.${this.user.id}`);
            const $this = this;
            channel.bind('NewMessage', function(data) {
                $this.handleIncomingMessages(data);
            });

            axios.get(`${url}/contacts`)
                .then(res => {
                   this.contacts = res.data;
                });
        },
        components:{Conversation, ContactList},
        methods:{
            startConversationWith(contact) {
                this.updateUnreadContacts(contact, true);
                axios.get(`${url}/conversation/${contact.id}`)
                    .then(res => {
                        this.messages = res.data;
                        this.selectedContact = contact;
                })
            },
            receivedNewMessage(message)
            {
                this.messages.push(message);
            },
            handleIncomingMessages(data) {
                if(this.selectedContact && data.message.from == this.selectedContact.id) {
                    this.receivedNewMessage(data.message);
                    return;
                }

                this.updateUnreadContacts(data.message.from_contact, false);

            },
            initPusher() {
                return new Pusher('d01f2441505008ff77e8', {
                    cluster: 'eu',
                    forceTLS: true
                });
            },
            updateUnreadContacts(contact, reset) {
                this.contacts = this.contacts.map((single) => {
                    if(single.id !== contact.id) {
                        return single;
                    }

                    if(reset == true) {
                        single.unread_messages = 0;
                    } else {
                        single.unread_messages++;
                    }
                    return single;
                });
            }
        }
    }
</script>
<style scoped lang="scss">
    .chat-app{
        display: flex;
    }
</style>
