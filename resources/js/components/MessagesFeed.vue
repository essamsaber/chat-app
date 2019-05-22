<template>
    <div class="feed" ref="feed">
        <ul v-if="contact">
            <li v-for="message in messages" :class="`message${message.to == contact.id ? ' sent': ' received'}`"
                :key="message.id">

                <div class="text">{{message.text}}</div>

            </li>
        </ul>
    </div>
</template>

<script>
    export default {
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
            scrollBottom(){
                setTimeout(() => {
                    this.$refs.feed.scrollTop = this.$refs.feed.scrollHeight - this.$refs.feed.clientHeight;
                }, 50);
            }
        },
        watch:{
            contact(contact){
              this.scrollBottom();
            },
            messages(message){
                this.scrollBottom();
            }
        }
    }
</script>

<style lang="scss" scoped>
    .feed{
        max-height: 470px;
        height: 100%;
        overflow: scroll;
        background: #f2f0f0;
        ul{
            list-style-type: none;
            padding:2px;

            li{
                &.message{
                    margin: 10px 0;
                    width: 100%;

                    .text{
                        max-width: 200px;
                        padding: 12px;
                        border-radius:5px;
                        display:inline-block;
                    }
                    &.received{
                        text-align:right;

                        .text{
                            background: #b2b2b2;
                        }
                    }

                    &.sent{
                        text-align: left;

                        .text{
                            background: #80bdff;
                        }
                    }

                }
            }
        }
    }
</style>