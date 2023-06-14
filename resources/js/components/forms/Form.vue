<template>
  <div class="mt-20">
    <input type="file" @change="uploadFile" accept=".srt" />
    <input type="text" v-model="searchText" placeholder="Enter word to search" />
    <button @click="search">Search</button>

    <div v-if="searchResults.length > 0">
      <h3>Search Results:</h3>
      <ul>
        <li v-for="(result, index) in searchResults" :key="index">{{ result }}</li>
      </ul>
    </div>

    <div v-if="wordCount > 0">
      <h3>Word Count: {{ wordCount }}</h3>
    </div>
    <div v-if="sentenceCount > 0">
      <h3>Sentence Count: {{ sentenceCount }}</h3>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fileContent: '',
      searchText: '',
      searchResults: [],
      wordCount: 0,
      sentenceCount: 0,
    };
  },
  methods: {
    uploadFile(event) {
      const file = event.target.files[0];
      const reader = new FileReader();

      reader.onload = (e) => {
        this.fileContent = e.target.result;
        this.wordCount = this.calculateWordCount(this.fileContent);
        this.sentenceCount = this.calculateSentenceCount(this.fileContent);
      };

      reader.readAsText(file);
    },
    search() {
      const lines = this.fileContent.split('\n');
      const results = [];

      for (let i = 0; i < lines.length; i++) {
          if(!lines[i].includes('-->')){
              results.push(lines[i]);
          }
      }

      this.searchResults = results;
    },
    calculateWordCount(text) {
      /* const words = text.split(/\s+/); // Split by whitespace characters
      return words.length; */
      const words = text.match(/\b\w+\b/g); // Match word boundaries and exclude non-word characters
      return words ? words.length : 0;
    },
    calculateSentenceCount(text) {
      const sentences = text.match(/[^.!?]+[.!?]+/g);
      const filteredSentences = sentences ? sentences.filter(sentence => sentence.trim() !== "") : [];
      console.log('filteredSentences',filteredSentences)
      return filteredSentences.length;
    },
  },
};
</script>



<!-- <template>
    <div class="container mt-20">
        <h1 class="text-lg py-3">Drag and Drop Form Elements</h1>
      <div class="row">
        <div class="col-md-3">
          <h3 class="text-md py-3 font-bold">Form Elements</h3>
          <draggable
            class="dragArea list-group"
            :list="formElements"
            :group="{ name: 'form', pull: 'clone', put: false }"
            @change="log"
            item-key="type"
          >
            <template #item="{ element }">
              <div class="list-group-item">
                <div class="flex">
                  <v-icon :icon="`mdi-${element.properties.icon}`"></v-icon>
                  <div class="pl-3">{{ element.name }}</div>
                </div>
              </div>
            </template>
          </draggable>

        </div>
        <div class="col-md-6">
          <div class="flex justify-between">
              <h3 class="text-md py-3 font-bold">Form Builder</h3>
              <h3 class="text-md py-3 pr-3">Page {{ page }}</h3>
              <v-btn @click="addSection">Add Section</v-btn>
              <v-btn @click="addPage">Add Page</v-btn>
          </div>
          
          <draggable
            v-for="(form, i) in formData[pageIndex].sections"
            class="dragArea list-group form-container p-3 my-2"
            :list="formData[pageIndex].sections[i].data_items"
            group="form"
            @change="log"
            item-key="type"
            :key="i"
          >    
            <template #item="{ element, index }">                
              <div class="px-3" :class="{ 'active-element': element.isActive }">
               <template v-if="element.type === 'text-field'">
                <div class="flex" @click="activeElement(element, index, i)">
                    <v-text-field
                      :placeholder="element === elementActive ? elementActive.properties.label : element.properties.label"
                      :hide-details="element.properties.hide_details"
                      :type="element.properties.type"
                      :variant="element.properties.variant"
                    ></v-text-field>
                    <v-icon class="ml-3 cursor-pointer" @click.stop="removeElement(i, index)" small>mdi-close</v-icon>
                </div>
              </template>
              <template v-else-if="element.type === 'label'">
                <div class="flex" @click="activeElement(element, index)">
                    <div class="text-lg w-100">{{ element.properties.label }}</div>
                    <v-icon class="ml-3 cursor-pointer" @click.stop="removeElement(i, index)" small>mdi-close</v-icon>
                </div>
              </template>
              <template v-else-if="element.type === 'textarea'">
                <div class="flex" @click="activeElement(element, index)">
                    <v-textarea
                      :label="element.properties.label"
                      :rows="element.properties.rows"
                      :variant="element.properties.variant"
                    ></v-textarea>
                    <v-icon class="ml-3 cursor-pointer" @click.stop="removeElement(i, index)" small>mdi-close</v-icon>
                </div>
              </template>
              <template v-else-if="element.type === 'column'">
                <div class="flex" @click="activeElement(element, index)">
                   
                    <v-icon class="ml-3 cursor-pointer" @click.stop="removeElement(i, index)" small>mdi-close</v-icon>
                </div>
              </template>
              </div>
            </template>
          </draggable>
          <div class="flex justify-between py-2">
            <v-btn @click="prevPage" :disabled="page - 1 < 1">Prev Page</v-btn>
            <v-btn @click="nextPage" :disabled="pageIndex + 2 > formData.length">Next Page</v-btn>
          </div>
        </div>
        <div class="col-md-3">
          <h3 class="text-md py-3 font-bold">Properties</h3>
            <div v-if="elementActive">
                {{ elementActive.properties }}
                <div>----------------------------</div>
                <element-properties
                  :elementActive="elementActive"
                  :pageIndex="pageIndex"
                  :sectionIndex="sectionIndex"
                  :formData="formData"
                  :index="position"
                ></element-properties>
            </div>
        </div>
      </div>
      <div>
        <v-btn @click="submitForm">Submit</v-btn>
      </div>
    </div>
</template>

 <script>
import draggable from 'vuedraggable'
import ElementProperties from './ElementProperties.vue'

 export default {
    components: {
      draggable,
      ElementProperties
    },
    data() {
        return {
          page: 1,
          formData: [
            {
              title: 'page 1',
              sections: [
                  {
                      title: 'section 1',
                      data_items: []
                  }
              ]
            },
          ],
          formElements: [
            {
                type: 'label',
                name: 'Label',
                isActive: false,
                properties: 
                {
                    variant: null,
                    label: 'Label',
                    rows: null,
                    prepend_icon: null,
                    clearable: false,
                    type: 'label',
                    hide_details: true,
                    append_icon: null,
                    prepend_inner_icon: null,
                    append_inner_icon: null,
                    required: false,
                    icon: 'format-text'
                }

            },
            {
                type: 'text-field',
                name: 'Text input',
                isActive: false,
                properties: 
                {
                    variant: 'outlined',
                    label: 'Text input',
                    prepend_icon: null,
                    clearable: false,
                    rows: null,
                    type: 'text',
                    hide_details: false,
                    append_icon: null,
                    prepend_inner_icon: null,
                    append_inner_icon: null,
                    required: false,
                    icon: 'format-text-rotation-none'
                }

            },
            {
                type: 'textarea',
                name: 'Textarea',
                isActive: false,
                properties: 
                {
                    variant: 'outlined',
                    prepend_icon: null,
                    clearable: false,
                    rows: 2,
                    hide_details: false,
                    append_icon: null,
                    prepend_inner_icon: null,
                    append_inner_icon: null,
                    auto_grow: false,
                    required: false,
                    icon: 'format-text-rotation-down-vertical'
                }

            },
            {
                type: 'radio',
                name: 'Radio button',
                isActive: false,
                properties: 
                {
                    variant: 'default',
                    clearable: false,
                    hide_details: false,
                    required: false,
                    icon: 'radiobox-marked',
                    list: [
                      {
                        label: 'Radio 1',
                        value: '1',
                      },
                    ]
                }
            },
            {
                type: 'checkbox',
                name: 'Checkbox',
                isActive: false,
                properties: 
                {
                    variant: 'default',
                    clearable: false,
                    hide_details: false,
                    required: false,
                    icon: 'checkbox-blank-outline',
                }
            },
            {
                type: 'select',
                name: 'Select',
                isActive: false,
                properties: 
                {
                    variant: 'default',
                    clearable: false,
                    hide_details: false,
                    required: false,
                    icon: 'form-dropdown',
                    list: [
                      {
                        label: 'Select 1',
                        value: '1',
                      },
                      {
                        label: 'Select 2',
                        value: '2',
                      },
                    ],
                    chips: false,
                    multiple: false,
                }
            },
            {
                type: 'image',
                name: 'Image',
                properties: 
                {
                    type: 'image',                    
                    icon: 'image-area',
                }
            },
            {
                type: 'file',
                name: 'Attachment',
                properties: 
                {
                    type: 'file',                    
                    icon: 'attachment',
                }
            },
          ],
          formBuilder: [],
          pages: [],
          element: null,
          pageIndex: 0,
          sectionIndex: 0,
          elementActive: null,
          placeholder: '',
          position: 0,
        };
      },
      methods: {
        log(evt) {
         console.log(evt);
         this.elementActive = null
        },
        addSection(){
          const data = {
            title: 'New Section',
            data_items: []
          }
          this.formData[this.pageIndex].sections.push(data)
          this.sectionIndex++;
        },
        addPage(){
          this.pageIndex++
          this.page++
          let data = {
            title: 'page ' + this.page,
            sections: [
                {
                    title: 'section 1',
                    data_items: []
                }
            ]
          }
          this.formData.push(data)
        },
        nextPage(){
          if(this.pageIndex < this.formData.length ){
            this.pageIndex++
            this.page++
          }
          console.log('form data lenght--', this.formData.length)
          console.log('form data--', this.formData)
        },
        prevPage(){
          if(this.page > 1){
            this.pageIndex--
            this.page--
          }
        },
        removeElement(i, index){
            this.formData[this.pageIndex].sections[i].data_items.splice(index, 1)
        },
        activeElement(element, index, i){
            console.log('active', element)
            this.element = element
            //this.elementActive = element
            this.position = index
            this.sectionIndex = i
            // Create a copy of the element and its properties
            this.elementActive = {
              ...element,
              properties: { ...element.properties },
            };
        },
        async submitForm(){
    
            const data = {
                form_title: 'sample title',
                form_image: null,
                pages: this.formData /* [
                    {
                        title: 'page 1',
                        sections: [
                            {
                                title: 'section 1',
                                data_items: this.formBuilder.map(item => ({
                                    label: item.label,
                                    type: item.type,
                                    position: item.position,
                                    properties: JSON.stringify(item.properties), // send it as an array
                                })),
                            }
                        ]
                    }
                ] */
            }

            console.log('Data to be sent:', data); 

            /* await axios.post('/create-form', data)
                .then(response => {
                // Handle the successful response
                console.log('Form submitted successfully!', response);
            })
            .catch(error => {
                // Handle the error response
                console.error('Form submission failed:', error);
            }); */

        }
      }
 }
 </script>

<style>
.form-element {
  padding: 10px;
  margin: 10px;
  background-color: #f1f1f1;
  cursor: move;
}

.form-container {
  min-height: 300px;
  border: 1px solid #ccc;
}
.active-element {
  background-color: lightblue;
  /* Add any other styles for the active element */
}
</style> -->