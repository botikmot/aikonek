<template>
    <div>
        <div class="pr-2 flex cursor-pointer">
            <v-badge dot bordered offset-x="2" offset-y="3" :color="`${data.active ? 'success' : ''}`">
                <v-avatar :image="`${ data.avatar != 'avatar.png' ? `storage/users-avatar/${data.avatar}` : 'images/avatar.png' }`"></v-avatar>
            </v-badge>
            <div class="pt-1 pl-2">{{ data.name }}</div>
        </div>          
    </div>
</template>

<script>
export default {
    props: ['data']
}
</script>