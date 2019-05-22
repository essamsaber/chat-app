<template>
    <div class="contacts-list">
       <ul>
           <li v-for="contact in sortedContacts" :key="contact.id" :class="{'selected':contact === selected}" @click="selectContact(contact)">
               <div class="avatar">
                   <img :src="contact.profile_image" :alt="contact.name">
               </div>
               <div class="contact">
                   <p class="name">{{contact.name}}</p>
                   <p class="email">{{contact.email}}</p>
                   <span class="unread" v-if="contact.unread_messages">{{contact.unread_messages}}</span>
               </div>
           </li>
       </ul>
    </div>
</template>

<script>
    export default {
        props:{
            contacts:{
                type:Array,
                default:[]
            }
        },
        data(){
            return {
                selected: this.contacts.length ? this.contacts[0] : null
            }
        },
        methods:{
            selectContact(contact) {
                this.selected = contact;
                this.$emit('selected', contact);
            }
        },
        computed:{
            sortedContacts() {
                return _.sortBy(this.contacts, [(contact) => {
                    if(contact == this.selected) {
                        return Infinity;
                    }
                    return contact.unread_messages;
                }]).reverse();
            }
        }
    }
</script>
<style scoped lang="scss">
    .contacts-list{
        flex:2;
        max-height:600px;
        overflow: scroll;
        border-left:1px solid #a6a6a6;

        ul{
            list-style-type: none;
            padding-left: 0px;

            li{
                display:flex;
                padding:2px;
                border-bottom:1px solid #aaaaaa;
                height:80px;
                position:relative;
                cursor: pointer;
                span.unread{
                    position: absolute;
                    right: 10px;
                    top: 5px;
                    background: #16f916;
                    padding: 2px;
                    border-radius: 50%;
                    display: flex;
                    justify-content: center;
                    width: 20px;
                    height: 20px;
                }
                .avatar {
                    flex:1;
                    display: flex;
                    align-items: center;

                    img {
                        width: 35px;
                        border-radios:50%;
                        margin:0 auto;
                    }
                }

                .contact{
                    flex:3;
                    font-size:10px;
                    overflow:hidden;
                    display:flex;
                    flex-direction:column;
                    justify-content:center;

                    p {
                        margin:0;
                        &.name{
                            font-weight:bold;
                        }
                    }
                }

                &.selected{
                    background: #cecece;
                }
            }
        }
    }
</style>