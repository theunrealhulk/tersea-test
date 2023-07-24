<script setup>
import { onMounted,ref } from 'vue'
import axios from 'axios';
const user=ref({})
const token =JSON.parse(localStorage.getItem('agent')).token
onMounted(()=>{
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

const cancel=(id)=>{
    console.log(id)
    let answer=confirm('do you really want to cancel this invite')
    if(answer){
        axios.delete(`http://localhost:8000/api/invite/${id}`,{
            headers:{Authorization:`Bearer ${token}`},
        }).then((res)=>{
            axios.get('http://localhost:8000/api/user',{
                headers:{Authorization:`Bearer ${token}`}
            })
            .then((response)=>{
                user.value = response.data
            })
            .catch((error)=>{
                console.error(error);
            })
        }).catch((err)=>{
            console.error(err);
        })
    }
}
</script>
<template>
    <div>
        <h3>My Invitations</h3>
        <table>
            <tr>
                <th>name</th>
                <th>email</th>
                <th>company</th>
                <th>status</th>
                <th>action</th>
            </tr>
            <tr v-for="invitation in user.invitations" :key="invitation.id">
                <td>{{ invitation.name }}</td>
                <td>{{ invitation.email }}</td>
                <td>{{ invitation.company.name }}</td>
                <td>{{ invitation.status }}</td>
                <td v-if="invitation.status=='pending'">
                    <a @click="cancel(invitation.id)" class="btn-floating btn-small waves-effect waves-light red">x</a>
                </td>
            </tr>
        </table>

    </div>
</template>