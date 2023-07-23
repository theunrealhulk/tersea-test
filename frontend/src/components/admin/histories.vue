<script setup>
import axios from 'axios'
import {onMounted,ref} from 'vue'
let history =ref([])
onMounted(() => {
    const token = JSON.parse(localStorage.getItem('agent')).token;
  axios
    .get('http://localhost:8000/api/history', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    .then((response) => {
        history.value=response.data.data
     
    })
    .catch((error) => {
      console.error(error);
    });
});

</script>
<template>
    <div>
        <h3>Activity log</h3>

        <ul>
            <li v-for="item in history" :key='item.id'>{{ item.log }}</li>
        </ul>
    </div>
</template>