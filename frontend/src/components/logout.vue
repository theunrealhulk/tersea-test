<script setup>
import { onMounted } from 'vue';
import  axios from 'axios'

const logout=()=>{
    let token=JSON.parse(localStorage.getItem("agent")).token
    console.log(token)

    axios.post('http://localhost:8000/api/logout',null,{
        headers:{Authorization:`Bearer ${token}`}
    }).then((res)=>{
        console.log(res.data)
         localStorage.removeItem('agent'); 
         window.location.reload();
    }).catch((err)=>{
        console.error(err);
    })
}
</script>

<template>
<span id="logout" @click="logout()" class="btn-floating btn-small waves-effect waves-light red"><i class="mdi mdi-power"></i></span>
</template>
<style scoped>
#logout{
  float: right;
}
</style>