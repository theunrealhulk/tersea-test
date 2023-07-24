<script setup>
import { onMounted, defineProps, reactive, ref } from 'vue';
import axios from 'axios'

const { user } = defineProps(['user']);
let companies = ref([])
const company = ref({
  name: null,
  website: null,
  about: null,
});
let updateCompany =  ref({
  name: null,
  website: null,
  about: null,
});
const token = JSON.parse(localStorage.getItem('agent')).token;
onMounted(()=>{
    axios.get(`http://localhost:8000/api/company`,{
        headers:{
            Authorization:`Bearer ${token}`
        }
    }).then((res)=>companies.value = res.data.data)
    .catch((err)=>{console.error(err)})
})
const edit=(id)=>{
    console.log("edit",id)
     companies.value.find((company) => {
        if(company.id === id){
            updateCompany.value=company
            console.log(updateCompany.value.id)
        }
    });
}
const remove=(id)=>{
    let answer=confirm('do you really want to delete this company')
    if(answer){
        axios.delete(`http://localhost:8000/api/company/${id}`,{
            headers:{Authorization:`Bearer ${token}`},
        }).then((res)=>{
            console.log
            axios.get('http://localhost:8000/api/company',{
                headers:{Authorization:`Bearer ${token}`}
            })
            .then((res)=>companies.value = res.data.data)
            .catch((err)=>{console.error(err)})
        }).catch((err)=>{
            alert(err.response.data.message)
            console.error(err.response.data.message);

        })
    }
}
const update=(()=>{
    axios.put(`http://localhost:8000/api/company/${updateCompany.value.id}`,updateCompany.value,{
        headers:{
            Authorization:`Bearer ${token}`
        }
    }).then((res)=>{
            console.log
            axios.get('http://localhost:8000/api/company',{
                headers:{Authorization:`Bearer ${token}`}
            })
            .then((res)=>companies.value = res.data.data)
            .catch((err)=>{console.error(err)})
        }).catch((err)=>{
            alert(err.response.data.message)
            console.error(err.response.data.message);

        })
})

const add=(()=>{
    axios.post(`http://localhost:8000/api/add-company`,company.value,{
        headers:{
            Authorization:`Bearer ${token}`
        }
    }).then((res)=>{
            console.log
            axios.get('http://localhost:8000/api/company',{
                headers:{Authorization:`Bearer ${token}`}
            })
            .then((res)=>companies.value = res.data.data)
            .catch((err)=>{console.error(err)})
        }).catch((err)=>{
            alert(err.response.data.message)
            console.error(err.response.data.message);

        })
})
let showDialog=ref(false)

const showConfirm=()=>{
    showDialog.value=true
    console.log(showDialog.value)
}
const closeConfirm=()=>{
    showDialog.value=false;
}
</script>
<template>
    <div>
        <h3>Add new Company</h3>
        <form @submit.prevent="add()">
            <div class="input-field col s6">
                <input id="icon_prefix" type="text" class="validate" name="name1" v-model="company.name">
                <label for="icon_prefix"><i class="mdi mdi-office-building"></i> company name</label>
            </div>

            <div class="input-field col s6">
                <input id="icon_prefix" type="text" class="validate" name="website1" v-model="company.website">
                <label for="icon_prefix"><i class="mdi mdi-web"></i> company website</label>
            </div>

            <div class="input-field col s6">
                <input id="icon_prefix" type="text" class="validate" name="about1" v-model="company.about">
                <label for="icon_prefix"><i class="mdi mdi-information"></i> about</label>
            </div>
            <button type="submit" class="waves-effect waves-light btn"><i class="mdi mdi-account-plus"></i> submit</button>
        </form>
        <h3>Update new Company</h3>
        <form @submit.prevent="update()">
            <div class="input-field col s6">
                <input id="icon_prefix" type="text" class="validate" name="name" v-model="updateCompany.name">
                <label for="icon_prefix"><i class="mdi mdi-office-building"></i> company name</label>
            </div>

            <div class="input-field col s6">
                <input id="icon_prefix" type="text" class="validate" name="website" v-model="updateCompany.website">
                <label for="icon_prefix"><i class="mdi mdi-web"></i> company website</label>
            </div>

            <div class="input-field col s6">
                <input id="icon_prefix" type="text" class="validate" name="about" v-model="updateCompany.about">
                <label for="icon_prefix"><i class="mdi mdi-information"></i> about</label>
            </div>
            <button type="submit" class="waves-effect waves-light btn"><i class="mdi mdi-content-save"></i> update</button>
        </form>

        <table >
            <tr>
                <th>name</th>
                <th>website</th>
                <th>about</th>
                <th>employees</th>
                <th>edit</th>
                <th>delete</th>
                <th>invite</th>
            </tr>
            <tr v-for="company in companies" :key="company.id">
                <td>{{ company.name }}</td>
                <td><a href="#">{{ company.website }}</a></td>
                <td>{{ company.about }}</td>
                <td>
                    <ul>
                        <li v-for="employee in company.employees">
                            {{ employee.user.name }}
                        </li>
                    </ul>
                    <!-- {{ company.employees.length }} -->
                </td>
                <td>
                    <a @click="edit(company.id)" class="btn-floating btn-small waves-effect waves-light green darken-2"><i class="mdi mdi-pencil"></i></a>
                </td>
                <td>
                    <a @click="remove(company.id)" class="btn-floating btn-small waves-effect waves-light red"><i class="mdi mdi-delete"></i></a>
                </td>
                <td>
                    <a @click="showConfirm(company.id)" class="btn-floating btn-small waves-effect waves-light blue darken-2"><i class="mdi mdi-account-plus"></i></a>
                </td>
            </tr>
        </table>
        <div class="dialog" v-if="showDialog">
            <a style="float:right" @click="closeConfirm()" class="btn-floating btn-small waves-effect waves-light red"><i class="mdi mdi-close"></i></a>
            <h4 slot="title">Invite User to Company?</h4>
            <div class="input-field col s5">
                <input id="icon_prefix" type="text" class="validate" name="name" v-model="name">
                <label for="icon_prefix"><i class="mdi mdi-account"></i> employee name</label>
            </div>

            <div class="input-field col s5">
                <input id="icon_prefix" type="text" class="validate" name="email" v-model="email">
                <label for="icon_prefix"><i class="mdi mdi-email"></i> employee email</label>
            </div>
            <div class="input-field col s2">
                <a  class="btn-floating btn waves-effect waves-light blue darken-2 pulse" ><i class="mdi mdi-send"></i></a>
            </div>
        </div>

    
    </div>
</template>
<style scoped>
.dialog{
    position: fixed;
    top: 30%; left: 50%; transform: translateX(-50%);
    border:solid 3px gray;
    padding:2em;
    background:#111;
    z-index:1212312312;
    border-radius:10px;
    box-shadow: 20px 0 10px  #111;

}
</style>