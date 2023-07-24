<script setup>
import {onMounted,reactive} from 'vue'
import axios from 'axios'
let employee=reactive({
  name:null,
  email:null,
  password:null,
  phone:null,
  address:null,
  birth_day:null
})
let id=window.location.search.split('=')[1]

onMounted(()=>{
  //get invitation id from url
  if (window.location.search!='')
  {
    axios.get(`http://localhost:8000/api/invitation/${id}`,null)
    .then((res)=>{
      employee.name = res.data.data.name
      employee.email = res.data.data.email
      console.log(  employee)
    })
    .catch((err)=>{
      console.log(err);
    })
  }
})
const registerEmployee=()=>{
  console.log(employee)

  axios.post(`http://localhost:8000/api/invitation/${id}`,employee)
    .then((res)=>{
      console.log(res)
      localStorage.setItem('agent',JSON.stringify({
        token:res.data.token,
        isAdmin:res.data.isAdmin
      }))
      
      window.location='/'
    })
    .catch((err)=>{
      console.log(err);
    })
}




</script>

<template>
    <div class="">
      <h5>Hi {{ employee.name }} please complete the form to confirm your profile</h5>
      <!-- form -->
      <form @submit.prevent="registerEmployee()">
        <div class="input-field col s12">
            <input id="icon_prefix" type="text" class="validate" name="name" v-model="employee.name" >
            <label for="icon_prefix"><i class="mdi mdi-account"></i> Name</label>
        </div>
        <div class="input-field col s12">
            <input id="icon_prefix" type="text" class="validate" name="email" v-model="employee.email" readonly>
            <label for="icon_prefix"><i class="mdi mdi-email"></i> email</label>
        </div>
        <div class="input-field col s12">
            <input id="icon_prefix" type="password" class="validate" name="password" v-model="employee.password" required>
            <label for="icon_prefix"><i class="mdi mdi-lock"></i> password</label>
        </div>
        <div class="input-field col s12">
            <input id="icon_prefix" type="text" class="validate" name="phone" v-model="employee.phone">
            <label for="icon_prefix"><i class="mdi mdi-phone"></i> Phone</label>
        </div>
        <div class="input-field col s12">
            <input id="icon_prefix" type="text" class="validate" name="address" v-model="employee.address">
            <label for="icon_prefix"><i class="mdi mdi-map-marker"></i> address</label>
        </div>
        <div class="input-field col s12">
            <input id="icon_prefix" type="date" class="validate" name="birth_day" v-model="employee.birth_day">
            <label for="icon_prefix"><i class="mdi mdi-cake-variant"></i> birth day</label>
        </div>
        <button type="submit" class="waves-effect waves-light btn"><i class="mdi mdi-content-save"></i> Save </button>
      </form>
    </div>
  </template>
  
  <style>


  </style>
  