<template>
    <div v-if="elementActive.properties.variant !== null">
      <v-select
        v-model="elementActive.properties.variant"
        label="Variant"
        :items="['default', 'outlined', 'underlined', 'solo']"
      ></v-select>
    </div>
    <div v-if="elementActive.properties.label !== null">
      <v-text-field
        v-model="elementActive.properties.label"
        label="Label"
      ></v-text-field>
    </div>
    <div v-if="elementActive.properties.rows !== null">
      <v-text-field
        v-model="elementActive.properties.rows"
        label="Rows"
        type="number"
      ></v-text-field>
    </div>
</template>
  

<script>
export default {
    props: ['elementActive', 'index', 'pageIndex', 'sectionIndex', 'formData'],
    mounted(){
      console.log('---prp', this.formData[this.pageIndex].sections[this.sectionIndex].data_items[this.index])
    }
}
</script>