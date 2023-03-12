import { createStore } from 'vuex'

export default createStore({

    state: {
        notification: null,
        user: [],
        followers: [],
        friend_requests_received: [],
        friend_requests_sent: [],
        friends: [],
        suggested_friends: [],
        dark: false,
      },
      mutations: {
        setUser(state, payload){
            state.user = payload
            state.followers = payload.followers
            state.friend_requests_received = payload.friend_requests_received
            state.friend_requests_sent = payload.friend_requests_sent
            state.friends = payload.friends
        },
        setFriends(state, payload){
            state.friends = payload
        },
        setSuggestedFriends(state, payload){
            state.suggested_friends = payload
        },
        logout(state, payload){
            state.user = payload;
            state.followers = [];
            state.friend_requests_received = [];
            state.friend_requests_sent = [];
            state.friends = [];
        },
        setIsDark(state, payload){
            state.dark = payload
        }
      },
      getters: {
        user(state) {
            return state.user
        },
        followers(state) {
            return state.followers
        },
        friend_requests_received(state) {
            return state.friend_requests_received
        },
        friend_requests_sent(state) {
            return state.friend_requests_sent
        },
        friends(state) {
            return state.friends
        },
        suggested_friends(state) {
            return state.suggested_friends
        },
        isDark(state){
            return state.dark
        }
      },
      actions: {
        setUser(context, payload){
            context.commit('setUser', payload)
        },
        async fetchFriends(context){
            await axios.get('/friends').then(response => {
                console.log('friends', response)
                if(response.data.success){
                    /* this.friends.push(request.sender)
                    this.friend_requests_received.forEach((item, i) => {
                        if(item.friend_id == request.sender.id){
                            this.friend_requests_received.splice(i, 1)
                        }
                    }) */
                }
            })
        },
        async fetchSuggestedFriends(context){
            await axios.get('/suggested-friends').then(response => {
                if(response.data.success){
                    console.log('suggested', response)
                    context.commit('setSuggestedFriends', response.data.suggestedfriends)
                }
            })
        },
        logoutUser(context){
            context.commit('logout', []);
        },
        darkTheme(context, payload) {
            context.commit('setIsDark', payload);
        }
      },
      modules: {
        
      }
})