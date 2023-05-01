<template>
    <v-card :theme="isDark ? 'dark' : 'light'" class="mt-5 mx-auto" elevation="0">
        <v-card-title>
            <h1 class="text-center">To-Do List</h1>
        </v-card-title>
        <v-card-text>
            <div class="mb-3">
                <v-text-field v-model="todoTitle" hide-details label="Add new task" @keyup.enter="submitTodo()" outlined></v-text-field>
                <v-btn class="mt-3" rounded="pill" color="info" elevation="0" @click="submitTodo()">Add Task</v-btn>
            </div>
            <v-divider></v-divider>
            <div :class="`${isDark ? 'bg-gray-600' : 'bg-gray-200'} my-3 p-2 relative rounded-md`" v-for="(task, index) in todos" :key="index">
                <div :class="`${task.completed ? 'line-through' : ''}`">{{ task.title }}</div>
                <div class="flex justify-between">
                    <div v-if="task.completed" class="flex"><span class="text-green-500 text-sm">Completed</span> <span class="text-gray-400 italic text-xs pl-2">{{ formatDate(task.updated_at) }}</span></div>
                    <div v-else></div>
                    <div class="flex">
                        <v-tooltip
                            location="bottom"
                        >
                            <template v-slot:activator="{ props }">
                                <v-icon @click="completeTask(task, index)" class="cursor-pointer mx-3" small v-bind="props" color="success">
                                    mdi-playlist-check
                                </v-icon>
                            </template>
                            <span>Complete</span>
                        </v-tooltip>
                        <v-tooltip
                            location="bottom"
                        >
                            <template v-slot:activator="{ props }">
                                <v-icon @click="deleteTask(task,index)" class="cursor-pointer" small v-bind="props" color="danger">
                                    mdi-playlist-remove
                                </v-icon>
                            </template>
                            <span>Remove</span>
                        </v-tooltip>
                    </div>
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
import moment from 'moment'
export default {
    data () {
        return {
            todoTitle: '',
            status: '',
        }
    },
    computed: {
        todos() {
            return this.$store.getters.todos
        },
        isDark(){
            return this.$store.getters.isDark
        },
    },
    methods: {
        formatDate(date){
            return moment(date).fromNow(true)
        },
        async submitTodo() {
            if (this.todoTitle) {
                    await axios.post('/create-todo', {
                    title: this.todoTitle,
                }).then((response) => {
                    console.log('todoss--',response)
                    this.todos.unshift(response.data.todo);
                    this.todoTitle = '';
                });
            }
        },
        deleteTask(todo){
            this.$store.dispatch('deleteTask', todo)
        },
        completeTask(todo){
            this.$store.dispatch('completeTask', todo)
            setTimeout(() => {
                this.sortedTodos()
            }, 2000); // 2 second delay
        },
        sortedTodos() {
            this.todos.sort((a, b) => {
                if (a.completed && !b.completed) {
                    return 1; // a comes first
                } else if (!a.completed && b.completed) {
                    return -1; // b comes first
                } else {
                    return 0; // preserve order
                }
            })
        }
    },
    mounted() {
        console.log('todosss---',this.todos)
    }
}
</script>