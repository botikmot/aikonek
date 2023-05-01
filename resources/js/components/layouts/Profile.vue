<template>
    <div class="">
        <div class="cover">
            <div class="gray-shade"></div>
            <figure>
                <img src="https://bootdey.com/img/Content/bg1.jpg" class="img-fluid h-80 w-100" alt="profile cover">
            </figure>
            <div class="cover-body flex justify-content-between align-items-center container mx-auto -mt-32 relative">
                <div class="flex">
                    <img class="profile-pic w-40 h-40 rounded-circle object-cover" :src="`${ user.avatar != 'avatar.png' ? `storage/users-avatar/${user.avatar}` : 'images/avatar.png' }`" alt="profile">
                    <div class="pl-6 profile-name pt-6 font-bold text-2xl capitalize">{{ user.name }}</div>
                </div>
                <div class="d-none d-md-block">
                    <button class="btn btn-primary btn-icon-text btn-edit-profile">
                        Edit profile
                    </button>
                </div>
            </div>
        </div>
        <div class="container mt-16">
            <div class="row flex">
                <div class="w-1/4">
                    Sidebar
                </div>
                <div class="w-1/2">
                   <div class="px-3">
                        <create-post @submitPost="postSuccess"></create-post>
                   </div>
                </div>
                <div class="w-1/4">
                    Sidebar
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CreatePost from '../posts/CreatePost.vue';
export default {
    props: ['user'],
    components: {
        CreatePost,
    },
    data () {
        return {
            posts: [],
            loading: false,
            scrollPosition: 0,
        }
    },
    methods: {
        postSuccess(value) {
            this.posts.unshift(value)
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
    },
    created() {
        this.$store.dispatch('setUser', this.user)
    },
}
</script>