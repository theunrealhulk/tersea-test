<script setup>
const login=()=>{
  console.log("logged in");
}
</script>
<template>
  <div class="login">
    <h1>Login page</h1><br>
    <form @submit.prevent="login()">
      <div class="input-field col s6">
          <input id="icon_prefix" type="text" class="validate">
          <label for="icon_prefix"><i class="mdi mdi-email"></i></label>
      </div>
      <div class="input-field col s6">
          <input id="icon_prefix" type="password" class="validate">
          <label for="icon_prefix"><i class="mdi mdi-lock"></i></label>
      </div>
      <button type="submit" class="waves-effect waves-light btn">connect</button>
    </form>
  </div>
</template>

<style scoped>



</style>
