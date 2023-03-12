<template>
    <v-app :theme="isDark ? 'dark' : 'light'">
        <div class="container mt-16">
            <div class="row justify-content-center">
                <div class="col-md-3 d-none d-md-block flex justify-center">
                    <div class="fixed w-1/6">
                        <div class="border-r-2">
                            <div class="flex justify-center pt-5">
                                <img class="w-40 h-40 rounded-circle object-cover" :src="`storage/users-avatar/${user.avatar}`"/>
                            </div>
                            <div class="flex font-bold text-lg justify-center py-3">{{ user.name }}</div>
                            <div class="border-t-2  border-b-2 flex">
                                <div class="w-1/2 text-center border-r-2 py-2">
                                    <div class="font-bold text-xl">{{ user.followers.length }}</div>
                                    <div class="text-gray-400">{{ user.followers.length > 1 ? 'Followers' : 'Follower' }}</div> 
                                </div>
                                <div class="w-1/2 text-center py-2">
                                    <div class="font-bold text-xl">{{ user.friends.length }}</div>
                                    <div class="text-gray-400">{{ user.friends.length > 1 ? 'Friends' : 'Friend' }}</div>
                                </div>
                            </div>
                            <div @click="sample" class="py-3 text-center cursor-pointer text-md text-blue-700 font-bold">View My Profile</div>
                        </div>
                        
                        <div v-if="suggested_friends.length" class="py-3 my-3 border-r-2">
                            <div class="font-bold border-b-2">
                                <div class="px-3 pb-2">People you might know</div>
                            </div>
                            <div class="flex justify-between px-3 pt-3" v-for="suggested in suggested_friends" :key="suggested.id">
                                <div class="flex">
                                    <div class="pr-2">
                                        <img class="w-8 h-8 rounded-circle object-cover" :src="`storage/users-avatar/${suggested.avatar}`"/>
                                    </div>
                                    <div class="pt-1 font-semibold">{{ suggested.name }}</div>
                                </div>
                                <div>
                                    <v-tooltip
                                        location="bottom"
                                    >
                                        <template v-slot:activator="{ props }">
                                            <v-btn
                                                icon
                                                v-bind="props"
                                                size="small" 
                                                elevation="0"
                                                @click="accepFriend(suggested)"
                                            >
                                                <v-icon color="blue-darken-2">
                                                    mdi-account-plus
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>{{ suggested.friend_request_sent ? 'Accept' : suggested.friend_request_received ? 'Request sent' : 'Add friend' }}</span>
                                    </v-tooltip>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="col-12 col-md-6">
                    <create-post @submitPost="postSuccess"></create-post>
                    <div class="py-2" ref="list">
                            <div v-if="loading" role="status" class="absolute -translate-x-1/2 -translate-y-1/2 top-2/4 left-1/2">
                                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                                <span class="sr-only">Loading...</span>
                            </div>
                            <post-feed v-for="post in posts" :key="post.id" :data="post" @friendAction="getFriendsActions"  @deletePost="deleteSuccess"></post-feed>
                    </div>
                </div>
                <div class="col-md-3 d-none d-md-block flex justify-center">
                    <div class="fixed w-1/6">
                        <div class="p-3 my-3 border-l-2">
                            <div>Current Weather</div>
                            <div>
                                <v-text-field
                                    v-model="location"
                                    label="Enter location"
                                    hide-details="auto"
                                    variant="underlined"
                                    @keyup.enter="weatherApp"
                                ></v-text-field>
                            </div>
                            <div v-if="askLocation" class="my-3">Loading...</div>
                            <div>
                                <div class="font-bold pt-3">{{ city }}</div>
                                <div class="flex justify-center">
                                    <div class="w-1/2 flex">
                                        <v-img :src="weather_image" :width="100"></v-img>
                                    </div>
                                </div>
                                <div class="flex justify-center font-bold text-lg">{{ temp }} °C</div>
                                <div class="flex justify-center font-bold">{{ description }}</div>
                                
                            </div>
                        </div>


                        <div class="p-3 border-l-2">
                            <div>
                                <v-text-field
                                    v-model="askString"
                                    label="Ask anything..."
                                    hide-details="auto"
                                    variant="underlined"
                                    @keyup.enter="submitAsk"
                                ></v-text-field>
                            </div>
                            <div v-if="askLoading" class="my-3">Loading...</div>
                            <div v-else class="my-3">{{ answer }}</div>

                            <div v-if="friend_requests_received.length"> 
                                <div v-for="request in friend_requests_received" :key="request.id"> 
                                <span class="font-bold">{{ request.sender.name }} </span> wants to be your friend.
                                <span @click="acceptRequest(request)" class="cursor-pointer pl-2 text-blue-600">Accept?</span>
                                <span class="cursor-pointer pl-2 text-red-600">Reject?</span>
                                </div>
                                
                            </div>
                        </div>


                        <div class="p-3 my-3 border-l-2">
                            <div>
                                <v-text-field
                                    v-model="askImage"
                                    label="Enter text to generate image..."
                                    hide-details="auto"
                                    variant="underlined"
                                    @keyup.enter="convertImage"
                                ></v-text-field>
                            </div>
                            <div v-if="askLoading" class="my-3">Loading...</div>
                            <div v-else class="my-3">
                                <v-img :src="image" />
                            </div>
                        </div>
                        
                        <div v-if="friends.length" class="py-3 my-3 border-l-2">
                            <div class="font-bold border-b-2">
                                <div class="px-3 pb-2">Friends</div>
                            </div>
                            <friends-list @click="messageUser(friend)" class="px-3 pt-3 w-100" v-for="friend in sortedFriends" :key="friend.id" :data="friend"></friends-list>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <v-snackbar
            v-model="snackbar"
            :timeout="timeout"
            location="bottom right"
            :color="mode"
            variant="tonal"
        >
            {{ text }}

            <template v-slot:actions>
                <v-btn
                color="blue"
                variant="text"
                @click="snackbar = false"
                >
                Close
                </v-btn>
            </template>
        </v-snackbar>
        <chat-widget :data="userData" :show="showChat" @closeShow="updateShow" @submitMessage="clientSendChannel" @isTyping="typingChannel"></chat-widget>
    </v-app>
</template>

<script>
import CreatePost from './posts/CreatePost.vue';
import PostFeed from './posts/PostFeed.vue'
import FriendsList from './layouts/FriendsList.vue'
import ChatWidget from './ChatWidget.vue'
import { Configuration, OpenAIApi } from "openai";

export default {
    props: ['user'],
    components: {
        CreatePost,
        PostFeed,
        FriendsList,
        ChatWidget,
    },  
    data () {
        return {
            posts: [],
            loading: false,
            askString: '',
            askImage: '',
            successPost: false,
            allLoaded: false,
            page: 1,
            scrollPosition: 0,
            answer: null,
            askLoading: false,
            image: null,
            askLocation: false,
            location: '',
            weather_image: '',
            temp: '',
            city: '',
            description: '',
            snackbar: false,
            timeout: 2000,
            text: '',
            mode: 'success',
            onlineUsers: [],
            userData: null,
            showChat: false,
            client_id: null,
            typeChannel: null,
        }
    },
    computed: {
        friend_requests_received() {
            return this.$store.getters.friend_requests_received
        },
        friend_requests_sent() {
            return this.$store.getters.friend_requests_sent
        },
        friends() {
            return this.$store.getters.friends
        },
        suggested_friends() {
            return this.$store.getters.suggested_friends
        },
        isDark(){
            return this.$store.getters.isDark
        },
        sortedFriends() {
            return this.friends.sort((a, b) => {
                if (a.active && !b.active) {
                    return -1; // a comes first
                } else if (!a.active && b.active) {
                    return 1; // b comes first
                } else {
                    return 0; // preserve order
                }
            })
        }

    },
    watch: {
    },  
    methods: {
        messageUser(user){
            this.userData = user
            this.showChat = true
            this.client_id = user.id
        },
        updateShow(value) {
            this.showChat = value
        },
        async accepFriend(user){
            if(!user.friend_request_received){
                let isfriend = 'request-friend'
                let data = {
                    user_id: user.id
                }
                if(user.friend_request_sent){
                    isfriend = 'accept-friend'
                    data = {
                        id: user.friend_request_sent_id,
                        friend_id: user.id
                    }
                }
                await axios.post(`/${isfriend}`, data).then(response => {
                        console.log(response)
                        if(response.data.success){
                            this.text = response.data.message
                            this.snackbar = true
                        }
                }).catch(error =>{
                    console.log('error', error)
                    this.text = 'Oooops!! Something went wrong!'
                    this.snackbar = true
                    this.mode = 'danger'
                })

            }
        },
        weatherApp() {
            const apiKey = "548bee13398d9b2042c95a5b7f041fef";
            navigator.geolocation.getCurrentPosition(
                (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                let apiUrl = ''
                if(this.location){
                    apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${this.location}&appid=${apiKey}&units=metric`;
                }else{
                    apiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`;
                }
                

                fetch(apiUrl)
                    .then((response) => response.json())
                    .then((data) => {
                        console.log('weather->', data)
                        switch (data.weather[0].main) {
                            case 'Clear':
                                this.weather_image = '/images/weather/clear.png'
                                break;
                            case 'Clouds':
                                this.weather_image = '/images/weather/cloud.png'
                                break;
                            case 'Haze':
                                this.weather_image = '/images/weather/mist.png'
                                break;
                            case 'Rain':
                                this.weather_image = '/images/weather/rain.png'
                                break;
                            case 'Snow':
                                this.weather_image = '/images/weather/snow.png'
                                break;
                            default:
                                this.weather_image = ''
                        }
                        this.temp = data.main.temp
                        this.city = data.name
                        this.description = data.weather[0].description
                        this.location = null
                    })
                    .catch((error) => console.log(error));
                },
                (error) => {
                console.log(error);
                }
            );
        },
        async getPosts() {
            this.loading = true
            this.scrollPosition = this.$refs.list.scrollTop;
            try {
                await axios.get('/posts?page=' + this.page).then(response => {
                    console.log('postsss',response)
                    if (response.data.posts.data.length) {                        
                        this.posts = this.posts.concat(response.data.posts.data);
                        
                        this.page++;
                        this.loading = false;
                        this.$nextTick(() => {
                            this.$refs.list.scrollTop = this.scrollPosition;
                        });
                    } else {
                        this.allLoaded = true;
                        this.loading = false;
                    }

                })   
            }catch (error) {
                this.loading = false
                console.log(error)
            }
        },
        postSuccess(value) {
            this.posts.unshift(value)
        },
        async acceptRequest(request){
            try {
                const data = {
                    id: request.id,
                    friend_id: request.sender.id
                }
                await axios.post('/accept-friend', data).then(response => {
                    console.log(response)
                    if(response.data.success){
                        this.friends.push(request.sender)
                        this.friend_requests_received.forEach((item, i) => {
                            if(item.friend_id == request.sender.id){
                                this.friend_requests_received.splice(i, 1)
                            }
                        })
                    }
                })
            }catch (error) {
                console.log(error)
            }
        },
        deleteSuccess(val) {
            this.posts.forEach((item, i) => {
                if(item.id == val){
                    this.posts.splice(i, 1)
                }
            })
        },
        getFriendsActions(val){
            if(val){
                this.getPosts()
            }
        },
        handleScroll() {
            let bottomOfWindow = (window.innerHeight + window.scrollY) >= document.body.offsetHeight;
            if (bottomOfWindow && !this.loading && !this.allLoaded) {
                this.getPosts();
            }
        },
        async submitAsk() {
            const configuration = new Configuration({
                apiKey: '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', //process.env.OPENAI_API_KEY,
            });
            let openai = new OpenAIApi(configuration);
            
            if(this.askString){
                try {
                    this.askLoading = true
                    const completion = await openai.createCompletion({
                        model: "text-davinci-003",
                        prompt: this.askString, 
                        temperature: 0.7,
                        max_tokens: 500,
                    });
                    if(completion.data.choices){
                        this.answer = completion.data.choices[0].text
                        this.askLoading = false
                        this.askString = ''
                    }
                } catch (error) {
                    console.error(error);
                    this.askLoading = false
                    this.askString = ''
                }
            }else{
                this.answer = null
            }
        },
        async convertImage() {
            const configuration = new Configuration({
                apiKey: process.env.OPENAI_API_KEY,
            });
            let openai = new OpenAIApi(configuration);
            
            if(this.askImage){
                try {
                    this.askLoading = true
                    const completion = await openai.createImage({
                        prompt: this.askImage, 
                        n: 1,
                        size: "512x512",
                    });
                    if(completion.data.data){
                        this.image = completion.data.data[0].url
                        this.askLoading = false
                        this.askImage = ''
                    }
                } catch (error) {
                    console.error(error);
                    this.askLoading = false
                    this.askImage = ''
                }
            }else{
                this.image = null
            }
        },
        listenForUsersOnline() {
                const channel = pusher.subscribe("presence-activeStatus")
                channel.bind('pusher:subscription_succeeded', (data) => {
                    console.log('subscription succeeded', data)
                    let active_ids = []
                    if (data.members) {
                        if (Array.isArray(data.members)) {
                            data.members.forEach((member) => {
                                if (member.id !== data.myID) {
                                    //console.log('User ID:', member.id);
                                    active_ids.push(member.id)
                                }
                            });
                        } else if (typeof data.members === 'object') {
                            Object.keys(data.members).forEach((key) => {
                                if (key !== data.myID) {
                                    //console.log('User ID:', key);
                                    active_ids.push(key)
                                }
                            });
                        }
                        this.friends.forEach(user => {
                            user.active = active_ids.some(id => id == user.id);
                        });
                    }
                });

                channel.bind('pusher:member_added', (data) => {
                    console.log('member added', data)
                    this.onlineUsers.push(data);
                    this.friends.forEach(user => { 
                        if(user.id == data.id){
                            user.active = true;
                        }
                    })
                });

                channel.bind('pusher:member_removed', (data) => {
                    this.onlineUsers = this.onlineUsers.filter(
                        (user) => user.id !== data.info.id
                    );
                    console.log('member removed', data)
                    this.friends.forEach(user => { 
                        if(user.id == data.id){
                            user.active = false;
                        }
                    })
                });
        },
        listenForMessages() {
            const self = this;
            const channel = pusher.subscribe(`private-chatify.${this.user.id}`)
            channel.bind("messaging", function (data) {
                data.to_id = parseInt(data.to_id)
                if (data.from_id === self.client_id && data.to_id === self.user.id) {
                    const messagesContainer = $(".chat-widget-body") //document.querySelector(".chat-widget-body");
                    console.log('container', messagesContainer)
                    messagesContainer.find(".messages").append(data.message);
                    this.scrollToBottom(messagesContainer);
                    //this.makeSeen(true);
                    // remove unseen counter for the user from the contacts list
                    //const contactItem = document.querySelector(`.messenger-list-item[data-contact="${this.client_id}"]`);
                    //contactItem.querySelector("tr > td > b").remove();
                }
            });

            channel.bind("client-typing", function (data) {
                if (data.from_id == self.client_id && data.to_id == self.user.id) {
                    const messagesContainer = $(".chat-widget-footer")
                    if(data.typing){
                        messagesContainer.find(".typing-indicator").show()
                    }else{
                        messagesContainer.find(".typing-indicator").hide();
                    }
                }
            });



        },
        clientSendChannel(user_id){
            this.client_id = user_id
            const channel = pusher.subscribe(`private-chatify.${user_id}`)
            channel.trigger("client-contactItem", {
                update_for: user_id, // Messenger
                update_to: this.user.id, // Me
                updating: true,
            });
        },
        typingChannel(status){
            this.typeChannel = pusher.subscribe(`private-chatify.${this.client_id}`)
            this.typeChannel.trigger("client-typing", {
                from_id: this.user.id, // Me
                to_id: this.client_id, // Messenger
                typing: status,
            });
            //this.listenForTyping(this.client_id)
        },
        scrollToBottom(container) {
            $(container)
                .stop()
                .animate({
                scrollTop: $(container)[0].scrollHeight,
            });
        },
        sample(){
            console.log('online users->', this.onlineUsers)
        }
    },
    mounted() {
        this.getPosts()
        this.weatherApp()
        window.addEventListener('scroll', this.handleScroll);
    },
    created() {
        this.$store.dispatch('setUser', this.user)
        this.$store.dispatch('fetchSuggestedFriends')
        this.listenForUsersOnline()
        this.listenForMessages()
        //this.listenForTyping()
    },
    beforeDestroy() {
        window.removeEventListener('scroll', this.handleScroll);
    }
}
</script>

<style scoped>
.elevation--1 {
    z-index: -1;
}
</style>
