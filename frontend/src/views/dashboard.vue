<script setup>
import { onMounted,ref } from 'vue'
import logout from '../components/logout.vue'
import axios from 'axios';
import addAdmin from '../components/admin/addAdmin.vue';
import companies from '../components/admin/companies.vue';
import invitations from '../components/admin/invitations.vue';
import histories from '../components/admin/histories.vue';


const user=ref({})
onMounted(()=>{
  const token =JSON.parse(localStorage.getItem('agent')).token
  M.Tabs.init(document.querySelector('.tabs'));
  axios.get('http://localhost:8000/api/user',{
    headers:{Authorization:`Bearer ${token}`}
  })
  .then((response)=>{
    user.value = response.data
  })
  .catch((error)=>{
    console.error(error);
  })
})
</script>

<template>
  <div class="container">
    <div class="row">
      <p>Hey {{ user.name }}<logout/></p>
    </div>
    <div class="row">
      <div class="col s12">
        <ul class="tabs">
          <li class="tab col s3"><a href="#admins" class="active">Admins</a></li>
          <li class="tab col s3"><a href="#companies">companies</a></li>
          <li class="tab col s3 "><a href="#invitations">invitations</a></li>
          <li class="tab col s3"><a href="#history">activity log</a></li>
        </ul>
      </div>
      <div id="admins" class="col s12">
        <addAdmin :user="user"/>
      </div>
      <div id="companies" class="col s12">
        <companies/>
      </div>

      <div id="invitations" :user="user" class="col s12">
        <invitations/>
      </div>

      <div id="history" class="col s12">
        <histories/>
      </div>
    </div>
  </div>
</template>
<style scoped>
.container{
  width: 100%;
  padding: 0;
  margin: 0;
}

</style>
