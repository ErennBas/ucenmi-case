
<template>
  <q-page class="bg-light-green window-height window-width row justify-center items-center">
    <div class="column">
      <div class="row">
        <h5 class="text-h5 text-white q-my-md">Ucemni</h5>
      </div>
      <div class="row">
        <q-card bordered class="q-pa-md shadow-5 card">
          <form @submit.prevent.stop="onSubmit">
            <q-card-section>
              <div class="q-gutter-md">
                <q-input ref="nameRef" v-model="name" :rules="nameRules" dense filled type="text" label="Name" />
                <q-input ref="emailRef" v-model="email" :rules="emailRules" dense filled type="email" label="Email" />
                <q-input ref="passwordRef" v-model="password" :rules="passwordRules" dense filled type="password" label="Password" />
              </div>
            </q-card-section>
            <q-card-actions class="q-px-md">
              <q-btn :loading="loading" type="submit" dense unelevated color="light-green-7" size="lg" class="full-width" label="Register" />
            </q-card-actions>
            <q-card-section class="text-center q-pt-md">
              <q-btn flat class="text-grey-6" to="/auth/login">I have an Account</q-btn>
            </q-card-section>
          </form>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import AuthService from 'src/services/auth.service'

const authService = new AuthService();

console.log("refleshed");
const loading = ref(false);


const name = ref(null);
const nameRef = ref(null);
const nameRules = [
  val => (val && val.length > 0) || "Name is required"
];

const email = ref(null);
const emailRef = ref(null);
const emailRules = [
  val => (val && val.length > 0) || "Email is required",
  val => isValidEmail(val) || "Invalid Email"
];

const password = ref(null);
const passwordRef = ref(null);
const passwordRules = [
  val => (val && val.length > 0) || "Password is required",
  val => (val && val.length > 8) || "Password must be at least 8 characters"
];

function isValidEmail(email) {
  const emailPattern = /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/;
  return emailPattern.test(email);
}

function onSubmit() {
  loading.value = true;
  nameRef.value.validate();
  emailRef.value.validate();
  passwordRef.value.validate();
  if (emailRef.value.validate() && passwordRef.value.validate() && nameRef.value.validate()) {
    authService.register(name.value, email.value, password.value).then(res => {
      console.log(res);
      if (res.status === 201) {
        authService.cookieService.set("token", res.data.message)
        window.location.href = "/";
      }
    })
  }
  else {
    loading.value = false;
  }
}

</script>

<style scoped>
.card {
  width: 300px;
  border-radius: 10px;
}
</style>