import { createStore } from 'vuex'

export default createStore({

    state: {
        user: [],
        followers: [],
        friend_requests_received: [],
        friend_requests_sent: [],
        friends: [],
        suggested_friends: [],
        dark: false,
        todos: [],
        notes: [],
        notif_data: {
            active: false,
            mode: '',
            message: ''
        },
        events: [],
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
        },
        setTodos(state, payload){
            state.todos = payload
        },
        removeTodo(state, payload){
            state.todos = state.todos.filter((t) => t.id !== payload.id);
        },
        completeTodo(state, payload){
            state.todos.forEach(item => {
                if(item.id == payload.id){
                    item.completed = true
                }
            })
        },
        setNotes(state, payload){
            state.notes = payload
        },
        updateNote(state, payload){
            state.notes.forEach(item =>{
                if(item.id == payload.id){
                    item.title = payload.title
                    item.content = payload.content

                    state.notif_data.active = true
                    state.notif_data.mode = 'success'
                    state.notif_data.message = 'Note successfully saved.'
                }
            })
        },
        removeNote(state, payload){
            state.notes = state.notes.filter((t) => t.id !== payload.id);
        },
        setEvents(state, payload){
            state.events = payload
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
        },
        todos(state){
            setTimeout(() => {
                state.todos.sort((a, b) => {
                    if (a.completed && !b.completed) {
                        return 1; // a comes first
                    } else if (!a.completed && b.completed) {
                        return -1; // b comes first
                    } else {
                        return 0; // preserve order
                    }
                })
            }, 2000);
            return state.todos
        },
        notes(state){
            return state.notes
        },
        notification(state){
            return state.notif_data
        },
        events(state){
            return state.events
        }
      },
      actions: {
        setUser(context, payload){
            localStorage.setItem('user', JSON.stringify(payload))
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
        },
        async fetchTodos(context){
            await axios.get('/todos').then(response => {
                if(response.data.success){
                    console.log('todos', response)
                    context.commit('setTodos', response.data.todos)
                }
            })
        },
        async deleteTask(context, payload){
            await axios.delete(`/remove-todo/${payload.id}`).then(res => {
                console.log('remove--todo', res)
                if(res.data.success){
                    context.commit('removeTodo', payload)
                }
            });
        },
        async completeTask(context, payload){
            await axios.post(`/complete-todo/${payload.id}`).then((response) => {
                if(response.data.success){
                    context.commit('completeTodo', payload)
                }
            });
        },
        async fetchNotes(context){
            await axios.get('/notes').then(response => {
                if(response.data.success){
                    console.log('notes', response)
                    context.commit('setNotes', response.data.notes)
                }
            })
        },
        async updateNote(context, payload){
            await axios.post(`/update-note/${payload.id}`, {
                title: payload.title,
                content: payload.content
            })
            .then(response => {
                if(response.data.success){
                    context.commit('updateNote', payload)
                }
            })
            .catch(error => {
                console.log(error);
            });
        },
        async deleteNote(context, payload){
            await axios.delete(`/remove-note/${payload.id}`).then(res => {
                console.log('remove--note', res)
                if(res.data.success){
                    context.commit('removeNote', payload)
                }
            });
        },
        async fetchEvents(context){
            await axios.get('/events').then(response => {
                if(response.data.success){
                    console.log('events', response)
                    context.commit('setEvents', response.data.events)
                }
            })
        },
      },
      modules: {
        
      }
})