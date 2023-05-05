<template>
  <q-layout view="hHh lpr fFf">
    <q-header elevated class="">
      <q-toolbar>
        <q-toolbar-title class="text-center">
          Ucenmi Blog
        </q-toolbar-title>


        <div class="q-pl-sm q-gutter-sm row items-center no-wrap">
          <q-btn flat>
            <q-avatar rounded size="30px">
              <img src="https://cdn.quasar.dev/img/boy-avatar.png">
            </q-avatar>
            <q-icon name="arrow_drop_down" size="32px" />

            <q-menu auto-close style="min-width: 200px!important;">
              <q-list>
                <q-item>
                  <q-item-section>
                    <div>Signed in as <strong>{{ user.name }}</strong></div>
                  </q-item-section>
                </q-item>
                <q-separator />
                <q-item clickable>
                  <q-item-section @click="logOut()">Sign out</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>

      </q-toolbar>
    </q-header>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import AuthService from "src/services/auth.service";

const authService = new AuthService();
const leftDrawerOpen = ref(false);
const user = authService.controlToken();

function logOut() {
  authService.logOut();
}


function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}



</script>

<style scoped>
.logout {
  position: absolute;
  width: 100%;
  bottom: 0;
}
</style>
