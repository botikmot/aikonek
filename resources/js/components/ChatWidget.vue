<template>
    <div class="chat-widget">
      <!-- <div v-if="!showChat" class="chat-widget-button" @click="showChat = !showChat">
        <v-icon>mdi-forum</v-icon>
      </div> -->
      <div v-if="showChat" class="chat-widget-container">
        <div class="chat-widget-header">
            <div class="flex">
                <v-avatar :image="`storage/users-avatar/${data.avatar}`"></v-avatar>
                <div class="pt-1">
                    <h3 class="chat-widget-title pl-3 font-bold">{{ data.name }}</h3>
                    <div class="pl-3 text-xs">{{ data.active ? 'Online now' : 'Offline' }}</div>
                </div>
            </div>
            <v-btn
                @click="showChat = false"
                variant="outlined"
                icon
                color="secondary"
                size="x-small"
            >
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </div>
        <div class="chat-widget-body">
          <!-- Chat messages -->
          <p class="messages" v-html="messages"></p>
            <!-- <div v-for="(message, index) in messages" :key="index" class="message-card" :class="message.sender ? 'mc-sender' : ''" :data-id="message.id" :title="message.timestamp">
                <div v-if="message.sender" class="chatify-d-flex chatify-align-items-center" style="flex-direction: row-reverse; justify-content: flex-end;">
                    <i class="fas fa-trash chatify-hover-delete-btn" :data-id="message.id"></i>
                    <p style="margin-left: 5px;">
                    {{ message.content }}
                    <sub :title="message.timestamp" class="message-time">
                        <span :class="message.seen ? 'fas fa-check-double seen' : 'fas fa-check seen'"></span> {{ message.timeAgo }}
                    </sub>
                    </p>
                </div>
                <div v-else>
                    <p>{{ message.content }}
                    <sub :title="message.timestamp">{{ message.timeAgo }}</sub>
                    </p>
                </div>
            </div> -->
        </div>
        <div class="chat-widget-footer relative">
            <div v-show="showEmojiPicker" class="absolute h-80 bg-gray-200 overflow-y-auto z-50 right-0 bottom-0">
                <EmojiPicker 
                    class="p-1"
                    :options="{imgSrc:'/emoji/img/',native:true,locals:'en',hasGroupIcons:true,hasSearch:false,
                        hasGroupNames:false,stickyGroupNames:false,hasSkinTones:false,
                        recentRecords:false,}"
                    @select="selectEmoji" 
                />
            </div>

            <div class="typing-indicator text-xs italic">Typing...</div>
            <v-textarea
                v-model="message"
                bg-color="white"
                label="Type your message"
                append-inner-icon="mdi-emoticon-outline"
                append-icon="mdi-send"
                @click:append="sendMessage"
                @click:append-inner="showEmojiPicker = !showEmojiPicker"
                @keydown.enter.prevent="sendMessage"
                @keydown.shift.enter.prevent="message += '\n'"
                @keydown="typeMessage"
                rows="1"
                hide-details
                auto-grow
            ></v-textarea>
<!--             <Picker @select="addEmoji" /> -->
        </div>
      </div>
    </div>
  </template>
  
<script>
//import { Picker } from 'emoji-mart-vue/dist/emoji-mart.js'
import { EmojiPicker } from 'vue3-twemoji-picker-final'
import axios from 'axios';

  export default {
    props: ['data', 'show'],
    components: {
        EmojiPicker,
    },
    data() {
      return {
            message: '',
            access_token: null,
            response: null,
            messages: null,
            tempID: 0,
            typingNow: 0,
            typingTimeout: null,
            showEmojiPicker: false,
      };
    },
    computed: {
        showChat: {
            get() {
                return this.show
            },
            set(value){
                this.$emit('closeShow', value)
            }
        },
        user() {
            return this.$store.getters.user
        },
        /* messages() {
            if(this.response){
                // Split the response into individual message cards
                    const cards = this.response.split('<div class="message-card');

                    // Remove the first item in the array (empty string before the first message card)
                    cards.shift();

                    // Loop through each message card and extract the relevant information
                    const messages = cards.map(function(card) {
                    // Extract the message content
                    const contentMatch = card.match(/<p style="margin\x2Dleft: 5px;">\s*([\s\S]*?)\s*<\/p>/);
                    const content = contentMatch ? contentMatch[1].trim() : null;

                    // Extract the message timestamp
                    const timeAgoMatch = card.match(/<sub title="[^"]+">(.+?)<\/sub>/);
                    const timeAgo = timeAgoMatch ? timeAgoMatch[1].trim() : null;

                    return {
                        content: content,
                        timeAgo: timeAgo
                    };
                    });

                    console.log(messages);

            }else {
                return []
            }
        }, */
    },
    watch: {
        data() {
            this.getMessages();
        }
    },
    methods: {
        selectEmoji(emoji) {
            // Store the emoji as Unicode or shortcode in the comments database
            this.message += emoji.i;
            this.showEmojiPicker = false
        },
        typeMessage(){
          if (this.typingNow < 1) {
            //isTyping(true);
            this.$emit('isTyping', true)
            this.typingNow = 1;
          }
          clearTimeout(this.typingTimeout);
          this.typingTimeout = setTimeout(() => {
            //isTyping(false);
            this.$emit('isTyping', false)
            this.typingNow = 0;
          }, 1000);
        },
        async sendMessage(){
          if (!event.shiftKey) {
            this.tempID += 1;
            const formData = new FormData();
            formData.append("id", this.data.id);
            formData.append("type", 'user');
            formData.append("temporaryMsgId", `temp_${this.tempID}`);
            formData.append("_token", this.access_token);
            formData.append("message", this.message);
              await axios.post('/messages/sendMessage', formData).then(res => {
                  console.log('send messages', res)
                  const messagesContainer = $(".chat-widget-body")
                  messagesContainer
                    .find(".messages")
                    .append(res.data.message);
                  this.scrollToBottom(messagesContainer);
                  this.$emit('submitMessage', this.data.id)
                  this.message = ''
              }).catch(err =>{
                  console.log(err)
              })

          }
        },
        async getMessages(){
            const data = {
                _token: this.access_token,
                id: this.data.id,
                type: 'user',
                page: 1, //messagesPage,
            }
            await axios.post('/messages/fetchMessages', data).then(res => {
                console.log('get messages', res)
                this.messages = res.data.messages
                const messagesContainer = $(".chat-widget-body")
                this.scrollToBottom(messagesContainer)
            }).catch(err =>{
                console.log(err)
            })
        },
        sendTempMessageCard(message, id) {
            return `
          <div class="message-card mc-sender" data-id="${id}">
            <p>
                ${message}
                <sub>
                    <span class="far fa-clock"></span>
                </sub>
            </p>
          </div>
          `;
        },
        scrollToBottom(container) {
            $(container)
                .stop()
                .animate({
                scrollTop: $(container)[0].scrollHeight,
            });
        },
    },
    mounted() {
        let metaTag = document.querySelector('meta[name="csrf-token"]');
        this.access_token = metaTag.getAttribute('content');
    },
    created() {
    }
  };
  </script>
  
  <style scoped>
  .chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
  }
  
  .chat-widget-button {
    background-color: #3498db;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    font-size: 24px;
    height: 60px;
    line-height: 60px;
    text-align: center;
    width: 60px;
  }
  
  .chat-widget-container {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    width: 350px;
    position: relative;
  }
  
  .chat-widget-header {
    background-color: #3498db;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    padding: 10px;
  }
  
  .chat-widget-title {
    margin: 0;
  }
  
  .chat-widget-close {
    background-color: transparent;
    border: none;
    color: #fff;
    cursor: pointer;
    font-size: 16px;
  }
  
  .chat-widget-close:hover {
    color: rgba(255, 255, 255, 0.8);
  }
  
  .chat-widget-body {
    height: 400px;
    overflow-y: scroll;
    padding: 10px;
  }
  
  .chat-widget-footer {
    border-top: 1px solid #ccc;
    padding: 10px;
  }
  </style>
  