<template>
    <div class="p-3 border-b-2">
        <div class="form-group flex">
            <div class="mr-3 w-1/12 flex justify-center pt-2">
                <v-avatar>
                    <v-img
                        cover
                        :src="`${ user.avatar ? `storage/users-avatar/${user.avatar}` : 'images/avatar.png' }`"
                        :alt="user.avatar"
                    ></v-img>
                </v-avatar>
                <!-- <img class="w-10 h-10 rounded-circle object-cover" :src="`storage/users-avatar/${this.user.avatar}`"/> -->
            </div>
            <v-textarea v-model="post" class="w-100" rows="2" label="What's on your mind?"></v-textarea>
        </div>
        <div class="form-group">
            <div class="flex mx-3 relative">
                <div class="flex">
                    <v-chip
                        class="ma-2 cursor-pointer"
                        color="primary"
                        label
                        size="small"
                        @click="$refs.attachments.click()"
                    >
                        <v-icon start icon="mdi-image"></v-icon>
                        Image
                    </v-chip>
                    <input type="file" ref="attachments" @change="handleFiles" class="form-control-file hidden" accept="image/*" multiple>
                    <input type="file" ref="video" @change="handleVideoFiles" class="form-control-file hidden" accept="video/mp4, video/quicktime, video/mkv">
                    <v-chip
                        class="ma-2 cursor-pointer"
                        color="success"
                        label
                        size="small"
                        @click="$refs.video.click()"
                    >
                        <v-icon start icon="mdi-video"></v-icon>
                        Video
                    </v-chip>
                    <v-menu offset-y>
                        <template v-slot:activator="{ props }">
                            <v-chip
                                class="ma-2 cursor-pointer"
                                :color="`${privacy == 'public' ? 'blue' : 'teal'}`"
                                label
                                size="small"
                                v-bind="props"
                            >
                                <v-icon start :icon="`${privacy == 'public' ? 'mdi-earth' : 'mdi-account-multiple'}`"></v-icon>
                                {{ privacy == 'public' ? 'Public' : 'Friends'}}
                            </v-chip>
                        </template>
                        <v-list :lines="false" density="compact" nav>
                            <v-list-item v-for="item in privacies" :key="item">
                                <v-list-item-title @click="privacy = item.value" class="cursor-pointer">{{ item.name }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
                <div class="absolute right-0">
                    <v-btn
                        rounded="pill"
                        color="info"
                        @click="submitPost"
                        elevation="0"
                    >
                        Post
                    </v-btn>
                   <!--  <div @click="submitPost" class="hover:bg-blue-600 hover:cursor-pointer bg-blue-200 hover:text-white px-3 py-2 rounded-md">Post</div> -->
                </div>
            </div>
        </div>
        <span v-if="attachments.length > 0" class="text-xs font-italic pl-5 flex">

            <v-chip
                class="ma-2 px-3"
                color="red"
                size="small"
            >
                <v-icon start icon="mdi-attachment"></v-icon>
                {{ attachments.length }} {{attachments.length > 1 ? 'attachments' : 'attachment'}}
            </v-chip>

            <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
            </svg>

            {{ attachments.length }} {{attachments.length > 1 ? 'attachments' : 'attachment'}} -->
        </span>
        
    </div>
</template>

<script>
export default {
    data () {
        return {
            post: '',
            privacy: 'public',
            attachments: [],
            privacies: [
                {
                    name: 'Public',
                    value: 'public'
                },
                {
                    name: 'Friends',
                    value: 'friends'
                }
            ]
        }
    },
    computed: {
        user() {
            return this.$store.getters.user
        },
    },
    methods: {
        async submitPost() {
            let formData = new FormData()
                formData.append('content', this.post)
                formData.append('privacy', this.privacy)

                for (let i = 0; i < this.attachments.length; i++) {
                    console.log('attchsss', this.attachments[i])
                    formData.append('attachments[]', this.attachments[i])
                }

            try {
                await axios.post('/create-post', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    if(response.data.success){
                        this.post = ''
                        this.privacy = 'public'
                        this.attachments = []
                        this.$emit('submitPost', response.data.post)
                    }
                })
            }catch (error) {
                console.log(error)
            }
        },
        handleFiles() {
            this.attachments = this.$refs.attachments.files
        },
        handleVideoFiles() {
            this.attachments = this.$refs.video.files
        }
    }
}
</script>