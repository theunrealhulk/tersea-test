<script setup>
import { onMounted,   ref,computed  } from 'vue';
import axios from 'axios'


let companies = ref([])
const company = ref({
  name: null,
  website: null,
  about: null,
});
let updateCompany =  ref({
  id:null,
  name: null,
  website: null,
  about: null,
});
const collection =ref([])
const user=ref({})

const token = JSON.parse(localStorage.getItem('agent')).token;
onMounted(()=>{
    //get all companies
    axios.get(`http://localhost:8000/api/company`,{
        headers:{
            Authorization:`Bearer ${token}`
        }
    }).then((res)=>{
        companies.value = res.data.data
        collection.value=companies.value
    })
    .catch((err)=>{console.error(err)})

    // get current User
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
const edit=(id)=>{
    console.log("edit",id)
     companies.value.find((company) => {
        if(company.id === id){
            updateCompany.value=company
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
let inViteCompany =ref({
    user_id:null,
    email:null,
    name:null,
    company_name:null,
    company_id:null
})
const showConfirm=(id)=>{
    showDialog.value=true
    console.log(id)
    console.log(user)
    companies.value.find((company) => {
        if(company.id === id){
            inViteCompany.value.company_name=company.name
            inViteCompany.value.company_id = company.id
            inViteCompany.value.user_id=user.id
        }
    });
    console.log(inViteCompany)
}
const closeConfirm=()=>{
    showDialog.value=false;
}

const sendInvite=()=>{
    console.log(inViteCompany.value)
    axios.post(`http://localhost:8000/api/invite/${inViteCompany.value.company_id}`,{
        user_id:inViteCompany.value.user_id,
        company_id:inViteCompany.value.company_id,
        name:inViteCompany.value.name,
        email:inViteCompany.value.email,
    },{
        headers:{
            Authorization:`Bearer ${token}`
        }
    }).
    then((res)=>{
        alert('an invitation mail has been sent to employee')
        
        window.location.reload()

        }).catch((err)=>{
            alert(err)
            console.error(err);

        })
}

const filterText = ref('');

function filteredCompanies() {
    const text = filterText.value.toLowerCase().trim();
    console.log(text) 
    if(text==""){
        collection.value=companies.value
        return
    }
    let  results= companies.value.filter((company) => {
      const nameMatch = company.name.toLowerCase().includes(text);
      const employeeMatch = company.employees.some(
        (employee) => employee.user.name.toLowerCase().includes(text)
      );
      return nameMatch || employeeMatch;
    });
    collection.value =  results.length==0?companies.value:results
    
  }
</script>
<template>
    <div>
        <h3>Add new Company</h3>
        <form @submit.prevent="add()">
            <div class="input-field col s6">
                <input id="icon_prefix" type="text"  name="name1" v-model="company.name">
                <label for="icon_prefix"><i class="mdi mdi-office-building"></i> company name</label>
            </div>

            <div class="input-field col s6">
                <input id="icon_prefix" type="text"  name="website1" v-model="company.website">
                <label for="icon_prefix"><i class="mdi mdi-web"></i> company website</label>
            </div>

            <div class="input-field col s6">
                <input id="icon_prefix" type="text"  name="about1" v-model="company.about">
                <label for="icon_prefix"><i class="mdi mdi-information"></i> about</label>
            </div>
            <button type="submit" class="waves-effect waves-light btn"><i class="mdi mdi-account-plus"></i> submit</button>
        </form>
        <h3>Update Company</h3>
        <form @submit.prevent="update()">
            <div class="input-field col s6">
                <input id="icon_prefix" type="text"  name="name" v-model="updateCompany.name">
                <label for="icon_prefix"><i class="mdi mdi-office-building"></i> company name</label>
            </div>

            <div class="input-field col s6">
                <input id="icon_prefix" type="text"  name="website" v-model="updateCompany.website">
                <label for="icon_prefix"><i class="mdi mdi-web"></i> company website</label>
            </div>

            <div class="input-field col s6">
                <input id="icon_prefix" type="text"  name="about" v-model="updateCompany.about">
                <label for="icon_prefix"><i class="mdi mdi-information"></i> about</label>
            </div>
            <button type="submit" class="waves-effect waves-light btn" :disabled="updateCompany.id === null"><i class="mdi mdi-content-save"  ></i> update</button>
        </form>
        <div class="input-field col s12">
                <input @input="filteredCompanies()" id="icon_prefix" type="text" v-model="filterText"  name="about" placeholder="    enter company name or employee name to see if there is a match"/>
                <label for="icon_prefix"><i class="mdi mdi-filter"></i> </label>
            </div>
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
            <tr v-for="company in collection" :key="company.id">
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
            <form>
                <a style="float:right" @click="closeConfirm()" class="btn-floating btn-small waves-effect waves-light red"><i class="mdi mdi-close"></i></a>
                <h5 slot="title">Invite new Employee to '<em>{{inViteCompany.company_name}}</em>' ?</h5>
                <div class="input-field col s5">
                    <input id="icon_prefix" type="text"  name="name" v-model="inViteCompany.name">
                    <label for="icon_prefix"><i class="mdi mdi-account"></i> employee name</label>
                </div>
                
                <div class="input-field col s5">
                    <input id="icon_prefix" type="text"  name="email" v-model="inViteCompany.email">
                    <label for="icon_prefix"><i class="mdi mdi-email"></i> employee email</label>
                </div>
                <div class="input-field col s2">
                    <a @click="sendInvite()" class="btn-floating btn waves-effect waves-light blue darken-2 pulse" ><i class="mdi mdi-send"></i></a>
                </div>
            </form>
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
    box-shadow: 10px 5px 10px  rgba(0,0,0,.7);
    width:-webkit-fill-available;
}
em{
    color:yellow;
}
</style>