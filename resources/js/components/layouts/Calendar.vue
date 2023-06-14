<template>
    <div>
      <VCalendar :attributes="attributes" transparent borderless expanded />
      <div>
        <v-btn @click="newEvent = !newEvent" prepend-icon="mdi-plus" class="w-100" rounded="pill" color="info" elevation="0">Create Event</v-btn>
      </div>
      <div v-if="newEvent" class="py-3">
        <v-text-field
            v-model="title"
            label="Title"
            required
            hide-details
            variant="underlined"
          ></v-text-field>
          <v-text-field
            v-model="description"
            label="Description"
            required
            hide-details
            variant="underlined"
          ></v-text-field>
          <div class="py-3">
            <VDatePicker v-model="range" mode="dateTime" is-range>
                <template #default="{ togglePopover }">
                <v-btn
                    class="w-100"
                    rounded="pill"
                    color="info"
                    elevation="0"
                    @click="togglePopover"
                >
                    Select date
                </v-btn>
                </template>
            </VDatePicker>
          </div>
          <div class="flex justify-between py-3">
            <v-text-field
                v-model="range.start"
                label="Start Date"
                required
                hide-details
                variant="underlined"
                readonly
            ></v-text-field>
            <v-text-field
                v-model="range.end"
                label="End Date"
                required
                hide-details
                variant="underlined"
                readonly
            ></v-text-field>
          </div>
          <div>
            <v-autocomplete
              v-model="participants"
              :items="friends"
              chips
              closable-chips
              color="blue-grey-lighten-2"
              item-title="name"
              item-value="id"
              label="Select Participants"
              multiple
              variant="underlined"
            >
              <template v-slot:chip="{ props, item }">
                <v-chip
                  v-bind="props"
                  :prepend-avatar="item.raw.avatar"
                  :text="item.raw.name"
                ></v-chip>
              </template>

              <template v-slot:item="{ props, item }">
                <v-list-item
                  v-bind="props"
                  :prepend-avatar="item?.raw?.avatar"
                  :title="item?.raw?.name"
                  :subtitle="item?.raw?.group"
                ></v-list-item>
              </template>
            </v-autocomplete>
          </div>
          <div class="flex justify-between">
            <v-btn @click="newEvent = false" rounded="pill" color="gray" elevation="0">Cancel</v-btn>
            <v-btn @click="saveEvent" rounded="pill" color="success" elevation="0">Save</v-btn>
          </div>
      </div>
      <div v-else class="p-3"> 
        <div v-if="events" >
            <div class="font-bold">Upcoming Events</div>
            <div v-for="event in events" :key="event.id">
              <v-list density="compact">
                <v-list-subheader>{{ event.title }}</v-list-subheader>

                <v-list-item
                  active-color="primary"
                >
                  <template v-slot:prepend>
                    <v-icon icon="mdi-flag"></v-icon>
                  </template>

                  <v-list-item-title>{{ event.description }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </div>
        </div>
      </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import moment from 'moment';

export default {
    data () {
        return {
            selectedDate: null,
            newEvent: false,
            title: '',
            description: '',
            menu1: false,
            menu2: false,
            participants: [],
            range: ref({
                start: moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
                end: moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
            }),
            attributes: ref([
                {
                    key: 'today',
                    highlight: true,
                    dates: new Date(),
                },
                /* {
                    highlight: {
                    start: { fillMode: 'outline' },
                    base: { fillMode: 'light' },
                    end: { fillMode: 'outline' },
                    },
                    dates: { start: new Date(2023, 4, 14), end: new Date(2023, 4, 18) },
                    popover: {
                        label: 'Sample description labels',
                    },
                }, */
            ]),
        }
    },
    computed: {
        computedDateFormatted () {
            return this.formatDate(this.date)
        },
        friends() {
            return this.$store.getters.friends
        },
        events() {
            return this.$store.getters.events
        }
    },

    watch: {
      range () {
        this.range.start = moment(this.range.start).format('YYYY-MM-DD HH:mm:ss')
        this.range.end = moment(this.range.end).format('YYYY-MM-DD HH:mm:ss')
      }
    },
    methods: {
        async saveEvent(){
            console.log('aprticipants', this.participants)
            console.log('range', this.range)
            await axios.post('/create-event', {
                title: this.title,
                description: this.description,
                start_date: this.range.start,
                end_date: this.range.end,
                participants: this.participants,
            }).then(response => {
                // Redirect to the created event page
                this.title = ''
                this.description = ''
                this.participants = []
                this.newEvent = false
                console.log('events--', response)
            }).catch(error => {
                console.log(error);
            });
        }
    },
    mounted() {
        if(this.events){
            this.events.forEach(event => {
                let data = {
                    highlight: {
                    start: { fillMode: 'outline' },
                    base: { fillMode: 'light' },
                    end: { fillMode: 'outline' },
                    },
                    dates: { start: new Date(event.start_date), end: new Date(event.end_date) },
                    popover: {
                        label: event.title,
                    },
                }
                this.attributes.push(data)
            });
        }
    }
}
</script>
