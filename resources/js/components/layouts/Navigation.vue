<template>
    <div>
        <v-app-bar :theme="dark ? 'dark' : 'light'" flat border class="">
            <!-- <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon> -->
            <v-toolbar-title class="cursor-pointer pt-2"><a href="/">AI-Konek</a></v-toolbar-title>
            <template v-slot:append>
                <v-tooltip
                    location="bottom"
                >
                    <template v-slot:activator="{ props }">
                        <v-btn
                            icon
                            v-bind="props"
                            elevation="0"
                            @click="isOpenDrawer('notes')"
                        >
                            <v-icon color="blue-darken-2">
                                mdi-note
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Notes</span>
                </v-tooltip>
                <v-tooltip
                    location="bottom"
                >
                    <template v-slot:activator="{ props }">
                        <v-btn
                            icon
                            v-bind="props"
                            elevation="0"
                            @click="isOpenDrawer('todo')"
                        >
                            <v-icon color="blue-darken-2">
                                mdi-format-list-checks
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>To do</span>
                </v-tooltip>
                <v-tooltip
                    location="bottom"
                >
                    <template v-slot:activator="{ props }">
                        <v-btn
                            icon
                            v-bind="props"
                            elevation="0"
                        >
                            <v-icon color="blue-darken-2">
                                mdi-bell
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Notifications</span>
                </v-tooltip>
                <v-btn icon class="">
                    <a href="/messages">
                        <v-icon color="blue">mdi-forum</v-icon>
                    </a>
                </v-btn>
                <v-btn
                    class=""
                    @click="isDark"
                    icon
                >
                    <v-icon color="orange">{{ dark ? 'mdi-weather-night' : 'mdi-weather-sunny'}}</v-icon>
                </v-btn>
                <v-menu offset-y>
                    <template v-slot:activator="{ props }">
                        <v-btn
                            icon
                            v-bind="props"
                        >
                            <v-avatar>
                                <img
                                    :src="`${ user.avatar != 'avatar.png' ? `storage/users-avatar/${user.avatar}` : 'images/avatar.png' }`"
                                    :alt="`${user.name}`"
                                >
                            </v-avatar>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item>
                            <v-list-item-title @click="logout" class="cursor-pointer">Logout</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>
        </v-app-bar>
        <v-navigation-drawer
            v-model="drawer"
            location="right"
            temporary
            elevation="0"
            width="400"
            class="p-3"
            :theme="dark ? 'dark' : 'light'"
        >
            <todos v-if="activeDrawer == 'todo'"></todos>
            <notes v-if="activeDrawer == 'notes'"></notes>
        </v-navigation-drawer>
    </div>
</template>

<script>
import axios from 'axios';
import Todos from './Todos.vue'
import Notes from './Notes.vue'

  export default {
    props: ['user'],
    components: {
        Todos,
        Notes
    },
    data: () => ({
      drawer: false,
      group: null,
      dark: false,
      activeDrawer: null,
    }),
    computed: {
    },

    watch: {
      group () {
        this.drawer = false
      },
    },
    methods: {
        isOpenDrawer(app){
            this.drawer = true
            this.activeDrawer = app
        },
        isDark() {
            this.dark = !this.dark
            this.$store.dispatch('darkTheme', this.dark)
        },  
        async logout(){
            await axios.post('/logout').then(res => {
                console.log('logout', res)
                localStorage.removeItem('user')
                location.reload();
            })
        },
    },
    mounted(){
        console.log('user nav', this.user)
    },
  }
</script>