<script setup>
import { onMounted, defineProps, reactive, ref } from 'vue';
import axios from 'axios';

const { user } = defineProps(['user']);

const admins = reactive([]);
const loading = ref(true);

const token = JSON.parse(localStorage.getItem('agent')).token;
onMounted(() => {
  axios
    .get('http://localhost:8000/api/admins', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    .then((response) => {
      admins.splice(0, admins.length, ...response.data.data); // Update the existing admins array
      loading.value = false; // Set loading to false after data is fetched
    })
    .catch((error) => {
      console.error(error);
      loading.value = false; // Set loading to false even in case of error
    });
});

const credentials = reactive({
  name: null,
  email: null,
  password: null,
});

const addAdmin = () => {

  console.log(credentials);
  axios.post('http://localhost:8000/api/add-admin',credentials, 
      {
        headers:{
            Authorization: `Bearer ${token}`,
        }
      })
  .then((response)=>{
        credentials.name=''
        credentials.email=''
        credentials.password=''
        //update admins
        axios
        .get('http://localhost:8000/api/admins', {
        headers: {
            Authorization: `Bearer ${token}`,
        },})
        .then((response) => {
        admins.splice(0, admins.length, ...response.data.data); // Update the existing admins array
        loading.value = false; // Set loading to false after data is fetched
        })
        .catch((error) => {
            console.error(error);
        });
  }).catch((error)=>{
    alert(error.message);
  });

};
</script>
<template>
    <div>
        <h3>Add new Admin</h3>
        <form @submit.prevent="addAdmin()">
            <div class="input-field col s6">
                <input id="icon_prefix" type="text" class="validate" name="name" v-model="credentials.name">
                <label for="icon_prefix"><i class="mdi mdi-account-tie"></i></label>
            </div>

            <div class="input-field col s6">
                <input id="icon_prefix" type="text" class="validate" name="email" v-model="credentials.email">
                <label for="icon_prefix"><i class="mdi mdi-email"></i></label>
            </div>

            <div class="input-field col s6">
                <input id="icon_prefix" type="password" class="validate" name="password" v-model="credentials.password">
                <label for="icon_prefix"><i class="mdi mdi-lock"></i></label>
            </div>
            <button type="submit" class="waves-effect waves-light btn"><i class="mdi mdi-account-plus"></i> submit</button>
        </form>
        <table class="striped">
            <tr>
                <th>name</th>
                <th>email</th>
            </tr>
            <tr v-for="admin in admins" :key="admin.id">
                <td>{{ admin.name }}</td>
                <td>{{ admin.email }}</td>
            </tr>
        </table>        
    </div>
</template>

