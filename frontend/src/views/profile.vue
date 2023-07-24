<script setup>
import { onMounted, defineProps, reactive, ref } from 'vue';
import { parseISO, differenceInYears, differenceInMonths, differenceInDays } from 'date-fns';

import logout from '../components/logout.vue'
import axios from 'axios';

let user = ref({});
const timeString = ref(''); // Replace this with your time string

const token = JSON.parse(localStorage.getItem('agent')).token;
onMounted(() => {

  axios.get('http://localhost:8000/api/user', {
    headers: { Authorization: `Bearer ${token}` }
  }).then((response) => {
      user.value = response.data;
      console.log(user.value);
    })
    .catch((error) => {
      console.error(error);
    });
});
const updateProfile=()=>{
  axios.put(`http://localhost:8000/api/employee/`,{
    phone:user.value.employee.phone.toString(),
    address:user.value.employee.address,
    birth_day:user.value.employee.birth_day
  },{
    headers:{Authorization:`Bearer ${token}`}
  })
    .then((res)=>{
      console.log(res)
      window.location='/'
    })
    .catch((err)=>{
      console.log(err);
    })
}

</script>
<template>
    <div class="about" v-if="user.employee!=undefined">

      <div class="row">
        <p>Hey {{ user.name }}<logout/></p>
      </div>

      <div class="row" >

        <div class="col s6">
          <div class="card blue-grey darken-1" >
            <div class="card-content white-text">
              <span class="card-title"><i class="mdi mdi-office-building"></i> {{user.employee.company.name}}</span>
              <p><i class="mdi mdi-information"></i> {{ user.employee.company.about }}</p>
              <p><i class="mdi mdi-web"></i> {{ user.employee.company.website }}</p>
            </div>
          </div>
        </div>

        <div class="col s6">
          <div class="card blue-grey darken-1">
            <div class="card-content white-text">
              <span class="card-title">Employees at {{user.employee.company.name}}</span>
              <ul>
                <li v-for="employee in user.employee.company.employees"> 
                  <i class="mdi mdi-account"></i> {{ employee.user.name }} 
                  <i class="mdi mdi-email"></i> {{ employee.user.email }}  
                  <i class="mdi mdi-calendar-account"></i> {{ employee.user.created_at.split('T')[0] }}  
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
        <h5> Personal info</h5>
        <!-- form -->
        <form @submit.prevent="updateProfile()">
          <div class="input-field col s12">
              <input id="icon_prefix" type="text" class="validate" name="name" v-model="user.name" readonly>
              <label for="icon_prefix"><i class="mdi mdi-account"></i> Name</label>
          </div>
          <div class="input-field col s12">
              <input id="icon_prefix" type="text" class="validate" name="email" v-model="user.email" readonly>
              <label for="icon_prefix"><i class="mdi mdi-email"></i> email</label>
          </div>
          <div class="input-field col s12">
              <input id="icon_prefix" type="text" class="validate" name="phone" v-model="user.employee.phone">
              <label for="icon_prefix"><i class="mdi mdi-phone"></i> Phone</label>
          </div>
          <div class="input-field col s12">
              <input id="icon_prefix" type="text" class="validate" name="address" v-model="user.employee.address">
              <label for="icon_prefix"><i class="mdi mdi-map-marker"></i> address</label>
          </div>
          <div class="input-field col s12">
              <input id="icon_prefix" type="date" class="validate" name="birth_day" v-model="user.employee.birth_day">
              <label for="icon_prefix"><i class="mdi mdi-cake-variant"></i> birth day</label>
          </div>
          <button type="submit" class="waves-effect waves-light btn"><i class="mdi mdi-content-save"></i> Save </button>
        </form>
    </div>


  </template>
  
  <style>
  
  </style>
  