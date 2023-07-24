<script setup>
import { reactive } from 'vue';
import router from '../router/index';
import axios from 'axios'

const credentials= reactive({
  email: null, password:null
})
const login=()=>{
  console.log(credentials);

  axios.post('http://localhost:8000/api/login',credentials)
  .then((response)=>{
    console.log(response.data);
    //redirect to dashboard
    localStorage.setItem('agent',JSON.stringify({
      isAdmin:response.data.isAdmin,
      token: response.data.token}));
      window.location.reload();
  }).catch((error)=>{
    alert(error.message);
  });
}
</script>
<template>
  <div class="login">
    <h1>Login page</h1><br>
    <form @submit.prevent="login()">
      <div class="input-field col s6">
          <input id="icon_prefix" type="text" class="validate" name="email" v-model="credentials.email" required>
          <label for="icon_prefix"><i class="mdi mdi-email"></i></label>
      </div>

      <div class="input-field col s6">
          <input id="icon_prefix" type="password" class="validate" name="password" v-model="credentials.password" required>
          <label for="icon_prefix"><i class="mdi mdi-lock"></i></label>
      </div>
      <button type="submit" class="waves-effect waves-light btn">connect</button>
    </form>
  </div>
</template>

<style >
input{
  color: white;
}


</style>
