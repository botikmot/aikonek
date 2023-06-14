<template>
    <v-card :theme="isDark ? 'dark' : 'light'" class="mt-5 mx-auto" elevation="0">
        <v-card-title>
            <h1 class="text-center">My Notes</h1>
        </v-card-title>
        <v-card-text>       
            <v-card-text class="px-0">
                <v-btn-toggle density="compact" variant="text" divided class="flex justify-between">
                    <div>
                        <v-tooltip
                            location="bottom"
                        >
                            <template v-slot:activator="{ props }">
                                <v-btn small @click="newNote = !newNote; isViewNote = false " icon v-bind="props" elevation="0">
                                    <v-icon small color="success">mdi-plus-circle-outline</v-icon>
                                </v-btn>
                            </template>
                            <span>Create Note</span>
                        </v-tooltip>
                    </div>
                    <div>
                        <v-btn @click="toggleBold" small>
                            <v-icon>mdi-format-bold</v-icon>
                        </v-btn>
                        <v-btn @click="toggleUnderline" small>
                            <v-icon>mdi-format-underline</v-icon>
                        </v-btn>
                        <v-btn @click="toggleItalic" small>
                            <v-icon icon="mdi-format-italic" small></v-icon>
                        </v-btn>
                    </div>
                </v-btn-toggle>
                <div v-if="newNote">
                    <v-text-field label="Title" v-model="newtitle"></v-text-field>
                    <div contenteditable="true" class="p-2" ref="noteEditor" v-html="newcontent"></div>
                    <div class="flex justify-end">
                        <v-tooltip
                            location="bottom"
                        >
                            <template v-slot:activator="{ props }">
                                <v-btn small @click="saveNote" icon v-bind="props" elevation="0">
                                    <v-icon small color="success">mdi-content-save</v-icon>
                                </v-btn>
                            </template>
                            <span>Save</span>
                        </v-tooltip>
                        <v-tooltip
                            location="bottom"
                        >
                            <template v-slot:activator="{ props }">
                                <v-btn small @click="newNote = false" icon v-bind="props" elevation="0">
                                    <v-icon small>mdi-close</v-icon>
                                </v-btn>
                            </template>
                            <span>Close</span>
                        </v-tooltip>
                    </div>
                </div>
                <v-divider></v-divider>
                <div v-if="isViewNote">
                    <v-text-field label="Title" v-model="title"></v-text-field>
                    <div contenteditable="true" class="p-2" ref="viewNoteEditor" v-html="content"></div>
                    <div class="flex justify-end">
                        <v-tooltip
                            location="bottom"
                        >
                            <template v-slot:activator="{ props }">
                                <v-btn small @click="updateNote" icon v-bind="props" elevation="0">
                                    <v-icon small color="success">mdi-content-save</v-icon>
                                </v-btn>
                            </template>
                            <span>Save</span>
                        </v-tooltip>
                        <v-tooltip
                            location="bottom"
                        >
                            <template v-slot:activator="{ props }">
                                <v-btn small @click="isViewNote = false" icon v-bind="props" elevation="0">
                                    <v-icon small>mdi-close</v-icon>
                                </v-btn>
                            </template>
                            <span>Close</span>
                        </v-tooltip>
                    </div>
                </div>
                <div v-else @click="viewNote(note)" :class="`${isDark ? 'bg-gray-600' : 'bg-gray-200'} my-3 p-2 flex justify-between relative rounded-md cursor-pointer`" v-for="(note, index) in notes" :key="index">
                    <div>{{ note.title }}</div>
                    <div>
                        <v-tooltip
                            location="bottom"
                        >
                            <template v-slot:activator="{ props }">
                                <v-icon small color="danger" @click.stop="removeNote(note)" v-bind="props">mdi-delete</v-icon>
                            </template>
                            <span>Delete</span>
                        </v-tooltip>
                    </div>
                </div>
            </v-card-text> 
        </v-card-text>
    </v-card>
</template>

<script>
export default {
  data() {
    return {
      title: '',
      newcontent: '',
      content: '',
      bold: false,
      underline: false,
      italic: false,
      isViewNote: false,
      note: null,
      newNote: false,
      newtitle: '',
    };
  },
  computed: {
    notes() {
        return this.$store.getters.notes
    },
    isDark(){
        return this.$store.getters.isDark
    },
  },
  methods: {
    removeNote(note){
        this.$store.dispatch('deleteNote', note)
    },
    viewNote(note){
        this.content = note.content
        this.isViewNote = true
        this.note = note
        this.title = note.title
        this.newNote = false
    },
    onInput(event) {
      this.content = event.target.textContent;
    },
    toggleBold() {
      this.bold = !this.bold;
      document.execCommand('bold', false, null);
    },
    toggleItalic() {
      this.italic = !this.italic;
      document.execCommand('italic', false, null);
    },
    toggleUnderline() {
      this.underline = !this.underline;
      document.execCommand('underline', false, null);
    },
    updateNote(){
        let content = this.$refs.viewNoteEditor.innerHTML
        const data = {
            id: this.note.id,
            title: this.title,
            content: content
        }
        this.$store.dispatch('updateNote', data)
    },
    async saveNote(event) {
      // Send note data to server for saving
      await axios.post('/create-note', {
        title: this.newtitle,
        content: this.$refs.noteEditor.innerHTML
      })
      .then(response => {
        console.log('note--', response.data);
        this.newNote = false
        this.newtitle = ''
        this.notes.unshift(response.data.note);
      })
      .catch(error => {
        console.log(error);
      });
    }
  }
};
</script>