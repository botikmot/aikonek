<template>
    <div class="py-3 my-3 z-unset border-b-2">
        <div class="flex justify-between pb-2 px-3">
            <div class="flex">
                <div class="pr-2">
                    <img class="w-10 h-10 rounded-circle object-cover" :src="`storage/users-avatar/${data.user.avatar}`"/>
                </div>
                <div>
                    <div><span class="font-bold">{{ data.user.name }}</span><span class="pl-2">posted an update</span></div>
                    <div class="text-sm font-italic text-gray-500 flex">
                        <svg v-if="data.privacy == 'friends'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 01-1.161.886l-.143.048a1.107 1.107 0 00-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 01-1.652.928l-.679-.906a1.125 1.125 0 00-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 00-8.862 12.872M12.75 3.031a9 9 0 016.69 14.036m0 0l-.177-.529A2.25 2.25 0 0017.128 15H16.5l-.324-.324a1.453 1.453 0 00-2.328.377l-.036.073a1.586 1.586 0 01-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 01-5.276 3.67m0 0a9 9 0 01-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25" />
                        </svg>
                        <span class="text-xs">{{ formatDate(data.created_at) }}</span>
                    </div>
                </div>
            </div>
            <div class="relative" ref="dropdown">
                <v-menu transition="slide-x-transition">
                    <template v-slot:activator="{ props }">
                        <v-btn
                            variant="flat"
                            icon="mdi-dots-horizontal"
                            v-bind="props"
                        ></v-btn>
                    </template>
                    <v-list :lines="false" density="compact" nav>
                        <v-list-item v-if="data.user.id !== user.id" class="py-0">
                            <v-list-item-title @click="isFriendUser" class="cursor-pointer py-0 text-sm">{{ isFriends ? 'Unfriend' : isFriendRequestSent ? 'Request Sent' : isAcceptFriend ? 'Accept Friend' : 'Add Friend' }}</v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="data.user.id !== user.id" class="py-0">
                            <v-list-item-title  @click="isFollowUser" class="cursor-pointer py-0 text-sm"> {{ isFollower ? 'Unfollow' : 'Follow' }}</v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="data.user.id == user.id" class="py-0">
                            <v-list-item-title @click="deletePost" class="cursor-pointer py-0 text-sm">Delete Post</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <!-- <button @click="togglePost" type="button" class="btn border-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </button>
                <div v-if="showPostAction" class="absolute bg-white shadow-sm p-2 right-8 top-5 w-40 z-one">
                    <div v-if="data.user.id !== user.id" @click="isFriendUser" class="px-3 cursor-pointer hover:bg-gray-300">{{ isFriends ? 'Unfriend' : isFriendRequestSent ? 'Request Sent' : isAcceptFriend ? 'Accept Friend' : 'Add Friend' }}</div>
                    <div v-if="data.user.id !== user.id" @click="isFollowUser" class="px-3 cursor-pointer hover:bg-gray-300 mt-2"> {{ isFollower ? 'Unfollow' : 'Follow' }}</div>
                    <div v-if="data.user.id == user.id" @click="deletePost" class="px-3 cursor-pointer hover:bg-gray-300 mt-2">Delete Post</div>
                </div> -->
            </div>
        </div>
        <p class="px-3 pb-2" v-html="data.content"></p>
        
        <div v-if="data.attachments.length">

            <div v-if="data.attachments.length == 1" class="single-image-container">
                <video v-if="determineMediaType(data.attachments[0]) == 'video'" ref="myVideo" @click="$refs.myVideo.play()" :src="data.attachments[0].path" controls></video>
                <v-img cover height="325" v-else :src="data.attachments[0].path" alt="Post Image" @click="expandImage(data.attachments[0].path, 0)" class="full-width-image max-height-image"></v-img>
            </div>
            <div v-else-if="data.attachments.length == 3" class="">
                <div class="single-image-container">
                    <img :src="data.attachments[0].path" alt="Post Image" @click="expandImage(data.attachments[0].path, 0)" class="full-width-image max-height-image"/>
                </div>
                <div class="image-grid">
                    <div v-for="(attachment, i) in data.attachments.slice(1, 3)" :key="attachment.id" class="grid-item">
                        <img @click="expandImage(attachment.path, i)" class="w-100 max-height-image" :src="attachment.path" height="200" />
                    </div>
                </div>
            </div>
            <div v-else class="image-grid">
                <div v-for="(attachment, i) in data.attachments.slice(0, 4)" :key="attachment.id" class="grid-item">
                
                    <img @click="expandImage(attachment.path, i)" class="w-100 max-height-image" :src="attachment.path" height="200" />
                    <div v-if="i === 3 && data.attachments.length > 4" class="more-images-overlay" @click="expandImage(data.attachments[3].path, i)">+{{data.attachments.length - 4}}</div>
                </div>
            </div>
            <div v-if="expandedImage" class="expanded-image-container">
                <img :src="expandedImage" alt="Expanded Image" @click="closeExpandedImage()" />
                <button class="text-white font-bold" @click="nextImage()">Next Image</button>
            </div>
        </div>
        <div class="py-2 border-b-1 border-t-1 mt-2 flex">
            <div @click="isLikePost" class="py-0 pl-3 pr-2 my-0 cursor-pointer">
                <svg v-if="liked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600">
                    <path d="M7.493 18.75c-.425 0-.82-.236-.975-.632A7.48 7.48 0 016 15.375c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75 2.25 2.25 0 012.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23h-.777zM2.331 10.977a11.969 11.969 0 00-.831 4.398 12 12 0 00.52 3.507c.26.85 1.084 1.368 1.973 1.368H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 01-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227z" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :class="`w-5 h-5`">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                </svg>

            </div>
            <div v-if="data.count_likes">{{ data.count_likes }}</div>
        </div>

        <div class="my-2 mx-3" v-if="data.comments.length">
            <div class="flex my-2 w-100" v-for="(comment, i) in data.comments" :key="comment.id">
                <div class="pr-2">
                    <v-avatar size="small">
                        <v-img
                            cover
                            :src="`storage/users-avatar/${comment.user.avatar}`"
                            :alt="comment.user.avatar"
                        ></v-img>
                    </v-avatar>
                </div>
                <v-sheet
                    class="d-flex px-2"
                    rounded
                    color="grey-lighten-2"
                    >
                    <div class="pb-3">
                        <div class="font-bold">{{ comment.user.name }} <span class="text-xs pl-1 font-light">{{ formatDate(comment.created_at) }}</span></div>
                        <div :contenteditable="editableComment" ref="commentEditor" @blur="updateComment" v-html="comment.body">
                        </div>
                        <div v-if="comment.attachment" class="w-100 relative">
                            <v-btn class="" v-if="editableComment" size="x-small" icon="mdi-close"></v-btn>
                            <v-img @click="expandImage(comment.attachment, 0)" :src="comment.attachment" :width="200"></v-img>
                        </div>
                    </div>
                </v-sheet>
                <div v-if="user.id == comment.user.id" class="pl-3">
                    <v-menu transition="slide-x-transition">
                        <template v-slot:activator="{ props }">
                            <v-btn
                                variant="flat"
                                icon="mdi-dots-horizontal"
                                v-bind="props"
                            ></v-btn>
                        </template>
                        <v-list :lines="false" density="compact" nav>
                            <v-list-item class="py-0">
                                <v-list-item-title @click="editComment(comment, i)" class="cursor-pointer py-0 text-sm">Edit</v-list-item-title>
                            </v-list-item>
                            <v-list-item class="py-0">
                                <v-list-item-title @click="removeComment(comment.id)" class="cursor-pointer py-0 text-sm">Delete</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </div>
            <div
                v-if="data.count_comments > 3 && nextCommentPage"
                @click="loadMoreComments"
                :class="`italic ${ loadingComments ? 'cursor-not-allowed' : 'cursor-pointer'} cursor-pointer text-sm text-blue-400`"
            >
                {{ loadingComments ? 'Loading...' : 'View more comments'}}
            </div>
        </div>

        <div class="flex pt-3 mx-3 relative" ref="emoji">
            <div v-show="showEmojiPicker" class="absolute h-80 bg-gray-200 overflow-y-auto z-50 right-0">
                <EmojiPicker 
                    class="p-1"
                    :options="{imgSrc:'/emoji/img/',native:true,locals:'en',hasGroupIcons:true,hasSearch:false,
                        hasGroupNames:false,stickyGroupNames:false,hasSkinTones:false,
                        recentRecords:false,}"
                    @select="selectEmoji" 
                />
            </div>


            <div class="pt-2">
                <v-avatar size="small">
                    <v-img
                        cover
                        :src="`storage/users-avatar/${user.avatar}`"
                        :alt="user.avatar"
                    ></v-img>
                </v-avatar>
            </div> 
            <div class="w-100" contenteditable="true" @paste="handlePaste">
                <v-textarea
                    class="mx-3"
                    v-model="comment"
                    bg-color="white"
                    label="Write a comment"
                    append-inner-icon="mdi-emoticon-outline"
                    append-icon="mdi-send"
                    @click:append="submitComment"
                    @click:append-inner="showEmojiPicker = !showEmojiPicker"
                    @keydown.enter.prevent="submitComment"
                    @keydown.shift.enter.prevent="comment += '\n'"
                    rows="1"
                    hide-details
                    auto-grow
                ></v-textarea>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import { EmojiPicker } from 'vue3-twemoji-picker-final'
//import TwemojiPicker from 'twemoji-picker';

export default {
    props: ['data'],
    components: {
        EmojiPicker,
    },
    data () {
        return {
            comment: '',
            showPostAction: false,
            is_follow: 'Follow',
            accept_id: null,
            expandedImage: null,
            currentIndex: 0,
            liked: false,
            images: this.data.attachments,
            attachments: [],
            imgContainer: null,
            isEditComment: false,
            editableComment: false,
            comment_id: null,
            loadingComments: false,
            commentPage: 0,
            nextCommentPage: true,
            showEmojiPicker: false,
        }
    },
    computed: {
        renderedComment() {
            // Convert the stored Unicode or shortcode to the corresponding emoticon
            return twemoji.parse(this.comment);
        },
        user() {
            return this.$store.getters.user
        },
        friend_requests_received() {
            return this.$store.getters.friend_requests_received
        },
        friend_requests_sent() {
            return this.$store.getters.friend_requests_sent
        },
        friends() {
            return this.$store.getters.friends
        },
        followers() {
            return this.$store.getters.followers
        },
        isFollower() {
            let follower = false
            console.log('user id', this.user.id)
            this.data.user.followers.forEach(item =>{
                if(item.follower_id == this.user.id){
                    follower = true
                }
            })
            console.log('follower', follower)
            return follower
        },
        isFriends() {
            let friend = false
            this.friends.forEach(item =>{
                if(item.id == this.data.user.id){
                    friend = true
                }
            })
            return friend
        },
        isFriendRequestSent() {
            let request_sent = false
            this.friend_requests_sent.forEach(item => {
                if(item.user_id == this.data.user.id){
                    request_sent = true
                }
            })
            return request_sent
        },
        isAcceptFriend() {
            let accept_friend = false
            this.friend_requests_received.forEach(item => {
                if(item.friend_id == this.data.user.id){
                    accept_friend = true
                    this.accept_id = item.id
                }
            })
            return accept_friend
        },
    },
    methods: {
        selectEmoji(emoji) {
            // Store the emoji as Unicode or shortcode in the comments database
            this.comment += emoji.i;
            this.showEmojiPicker = false
        },
        loadMoreComments() {
            this.loadingComments = true;
            this.commentPage++;
            axios.get(`/posts/${this.data.id}/comments/${this.commentPage}`)
            .then(response => {
                console.log('more comments', response)
                if(this.commentPage == 1){
                    this.data.comments = response.data.comments.data;
                }else{
                    this.data.comments = this.data.comments.concat(response.data.comments.data);
                }
                this.nextCommentPage = response.data.comments.next_page_url ? true : false
                this.commentPage = response.data.comments.current_page;
                this.loadingComments = false;
            })
            .catch(error => {
                console.log(error);
                this.loadingComments = false;
            });
        },
        editComment(comment, i){
            this.editableComment = true
            this.$nextTick(() => {
                this.$refs.commentEditor.focus();
            });
            this.comment_id = comment.id
        },
        async updateComment(event){
            if(!this.comment_id){
                return
            }
            console.log('update comment', event.target.innerHTML)
            try {
                const data = {
                    body: event.target.innerHTML
                }
                await axios.post(`/edit-comment/${this.comment_id}`, data).then(response => {
                    if(response.data.success){
                        console.log('edit comment', response)
                    }
                })
            }catch (error) {
                console.log(error)
            }
        },
        handlePaste(event) {
            const items = (event.clipboardData || event.originalEvent.clipboardData).items;

            for (const item of items) {
                if (item.type.indexOf('image') === 0) {
                    const file = item.getAsFile();
                    const reader = new FileReader();
                    this.attachments.push(file)
                    reader.readAsDataURL(file);
                    reader.onload = () => {
                        this.imgContainer = document.createElement('div');
                        this.imgContainer.classList.add('d-flex');
                        this.imgContainer.classList.add('align-items-center');

                        const img = document.createElement('img');
                        img.src = reader.result;
                        img.classList.add('px-3');
                        img.classList.add('pt-3');
                        img.classList.add('w-1/4');
                        this.imgContainer.appendChild(img);

                        const cancelBtn = document.createElement('button');
                        cancelBtn.innerText = 'Cancel';
                        cancelBtn.classList.add('px-3');
                        cancelBtn.classList.add('py-2');
                        cancelBtn.classList.add('bg-red-500');
                        cancelBtn.classList.add('text-white');
                        cancelBtn.classList.add('rounded-md');
                        cancelBtn.classList.add('mt-2');
                        cancelBtn.addEventListener('click', () => {
                            this.$el.removeChild(this.imgContainer);
                        });
                        this.imgContainer.appendChild(cancelBtn);
                        this.$el.appendChild(this.imgContainer);
                    };
                }
            }
        },
        determineMediaType(post) {
            let imageExtensions = ["jpg", "jpeg", "png", "gif", "svg"];
            let videoExtensions = ["mp4", "avi", "mov", "mkv"];
            let mediaType = 'other';
            let fileExtension = post.path.split('.').pop();

            if (imageExtensions.indexOf(fileExtension) !== -1) {
                mediaType = 'image';
            } else if (videoExtensions.indexOf(fileExtension) !== -1) {
                mediaType = 'video';
            }
            return mediaType;
        },
        expandImage(image, index) {
            this.expandedImage = image
            this.currentIndex = index
            this.$viewerApi({
                images: this.images,
            })
        },
        isLike() {
            this.data.likes.forEach(item => {
                if(item.user_id == this.user.id){
                    this.liked = true
                }
            })
        },
        closeExpandedImage() {
            this.expandedImage = null
        },
        nextImage() {
            if (this.currentIndex === this.data.attachments.length - 1) {
                this.currentIndex = 0
            } else {
                this.currentIndex++
            }
            this.expandedImage = this.data.attachments[this.currentIndex].path
        },
        togglePost(){
            this.showPostAction = !this.showPostAction
        },
        formatDate(date){
            return moment(date).fromNow(true)
        },
        async isLikePost(){
            try {
                await axios.post(`/post/${this.data.id}/like`).then(response => {
                    console.log('like', response)
                    if(response.data.success){
                        if(response.data.message == 'liked'){
                            this.data.count_likes++
                            this.liked = true
                        }else {
                            this.data.count_likes--
                            this.liked = false
                        }
                    }
                })
            }catch (error) {
                console.log(error)
            }
        },
        /* isFollow(){
            if(this.user.followers.length){
                this.user.followers.forEach(element => {
                    if(element.follower_id == this.user.id){
                        this.is_follow = 'Unfollow'
                    }
                });
            }
        }, */
        async isFriendUser(){
            if(!this.isFriendRequestSent && !this.isAcceptFriend){
                try {
                    const data = {
                        user_id: this.data.user.id
                    }
                    let isfriend = 'request-friend'
                    if(this.isFriends){
                        isfriend = 'unfriend'
                    }
                    await axios.post(`/${isfriend}`, data).then(response => {
                        console.log(response)
                        if(response.data.success){
                            this.showPostAction = false
                            if(this.isFriends){
                               this.friends.forEach((item, i) => {
                                    if(item.id == this.data.user.id){
                                        this.friends.splice(i, 1)
                                    }
                               })
                            }else{
                                const data = {
                                    user_id: this.data.user.id,
                                    friend_id: this.user.id,
                                    receiver: this.data.user
                                }
                                this.friend_requests_sent.push(data)
                            }

                        }
                    })
                }catch (error) {
                    console.log(error)
                }
            }else{
                if(this.isAcceptFriend){
                    try {
                        const data = {
                            id: this.accept_id,
                            friend_id: this.data.user.id
                        }
                        await axios.post('/accept-friend', data).then(response => {
                            if(response.data.success){
                                console.log(response.data.message)
                                this.friends.push(this.data.user)
                                this.friend_requests_received.forEach((item, i) => {
                                    if(item.friend_id == this.data.user.id){
                                        this.friend_requests_received.splice(i, 1)
                                    }
                                })
                            }
                        })
                    }catch (error) {
                        console.log(error)
                    }
                }
                this.showPostAction = false
            }
        },
        async isFollowUser(){
            let isfollow = 'follow'
            if(this.isFollower){
                isfollow = 'unfollow'
            }
            try {
                await axios.get(`/${isfollow}/${this.data.user.id}`).then(response => {
                    if(response.data.success){
                        this.showPostAction = false
                        console.log(response.data.message)
                        if(this.isFollower){
                            this.data.user.followers.forEach((item,i) => {
                                if(item.follower_id == this.user.id){
                                    this.data.user.followers.splice(i, 1)
                                }
                            })
                        }else{
                            const data = {
                                user_id: this.data.user.id,
                                follower_id: this.user.id
                            }
                            this.data.user.followers.push(data)
                        }   
                    }
                })
            }catch (error) {
                console.log(error)
            }
        },
        async deletePost(){
            try {
                await axios.delete(`/delete-post/${this.data.id}`).then(response => {
                    if(response.data.success){
                        this.showPostAction = false
                        this.$emit('deletePost', this.data.id)
                    }
                })
            }catch (error) {
                console.log(error)
            }
        },
        async removeComment(id) {
            try {
                await axios.post(`/delete-comment/${id}`).then(response => {
                    if(response.data.success){
                        console.log('comment', response)
                        this.data.comments.forEach((item, i) => {
                            if(item.id == id){
                                this.data.comments.splice(i, 1)
                            }
                        })
                        /* const comment = {
                            body: this.comment,
                            user: {
                                avatar: this.user.avatar,
                                name: this.user.name,
                                id: this.user.id
                            },
                            attachment: response.data.comment.attachment
                        }
                        this.data.comments.push(comment)
                        this.comment = ''
                        this.attachments = []
                        this.$el.removeChild(this.imgContainer) */
                    }
                })
            }catch (error) {
                console.log(error)
            }
        },
        async submitComment(){
            if (!event.shiftKey) {
                let formData = new FormData()
                    formData.append('body', this.comment)
                    for (let i = 0; i < this.attachments.length; i++) {
                        console.log('atta', this.attachments[i])
                        formData.append('attachment[]', this.attachments[i])
                    }
                try {
                    await axios.post(`/create-comment/${this.data.id}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        if(response.data.success){
                            console.log('comment', response.data)
                            const comment = {
                                body: this.comment,
                                user: {
                                    avatar: this.user.avatar,
                                    name: this.user.name,
                                    id: this.user.id
                                },
                                attachment: response.data.comment.attachment,
                                id: response.data.comment.id
                            }
                            this.data.comments.push(comment)
                            this.comment = ''
                            this.attachments = []
                            this.$el.removeChild(this.imgContainer)
                        }
                    })
                }catch (error) {
                    console.log(error)
                }
            }
        },
        handleClickOutside(e) {     
            if (!this.$refs.emoji.contains(e.target)) {
                this.showEmojiPicker = false
            }
        }
    },
    mounted() {
        //this.isFollow()
        this.isLike()
        document.addEventListener('click', this.handleClickOutside)
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside)
    },
}
</script>

<style scoped>
.z-unset {
    z-index: unset !important;
}
.z-one {
    z-index: 1;
}
</style>