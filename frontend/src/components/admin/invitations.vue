<script setup>
import { onMounted, defineProps, reactive, ref } from 'vue';
const { user } = defineProps(['user']);
</script>
<template>
    <div>
        <h3>My Invitations</h3>
        {{ user  }}
    </div>
</template>