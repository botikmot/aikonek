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
                        >
                            <v-icon color="blue-darken-2">
                                mdi-bell
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Notifications</span>
                </v-tooltip>
                <v-btn icon class="mx-3">
                    <a href="/messages">
                        <v-icon color="blue">mdi-forum</v-icon>
                    </a>
                </v-btn>
                <v-btn
                    class="mr-3"
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
                                    :src="`storage/users-avatar/${user.avatar}`"
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
        <!-- <v-navigation-drawer
            v-model="drawer"
            location="bottom"
            temporary
        >
            <v-list
                :items="items"
            ></v-list>
        </v-navigation-drawer> -->
    </div>
</template>

<script>
import axios from 'axios';

  export default {
    data: () => ({
      drawer: false,
      group: null,
      dark: false,
    }),
    computed: {
        user() {
            return this.$store.getters.user
        },
    },

    watch: {
      group () {
        this.drawer = false
      },
    },
    methods: {
        isDark() {
            this.dark = !this.dark
            this.$store.dispatch('darkTheme', this.dark)
        },  
        async logout(){
            await axios.post('/logout').then(res => {
                console.log('logout', res)
                location.reload();
            })
        }
    },
    mounted(){
        console.log('user nav', this.user)
    }
  }
</script>