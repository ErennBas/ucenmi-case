<template>
  <q-page class="flex flex-center">
    <div class="q-pa-md">
      <div class="flex flex-center ">
        <q-btn color="primary" icon="add" outline label="new Post" @click="newPost = true" />
      </div>
      <q-card class="my-card q-my-md" v-if="loading" v-for="i in [1, 2, 3, 4, 5]" flat bordered style="width: 500px">
        <q-item>
          <q-item-section avatar>
            <q-skeleton type="QAvatar" animation="fade" />
          </q-item-section>

          <q-item-section>
            <q-item-label>
              <q-skeleton type="text" animation="fade" />
            </q-item-label>
            <q-item-label caption>
              <q-skeleton type="text" animation="fade" />
            </q-item-label>
          </q-item-section>
        </q-item>

        <q-skeleton height="200px" square animation="fade" />

        <q-card-section>
          <q-skeleton type="text" class="text-subtitle2" animation="fade" />
          <q-skeleton type="text" width="50%" class="text-subtitle2" animation="fade" />
        </q-card-section>
      </q-card>

      <q-infinite-scroll v-if="!loading" @load="onLoad" :offset="250" style="max-width: 500px;">
        <q-card class="my-card q-my-lg" v-for="item in posts">
          <q-item>
            <q-item-section avatar>
              <q-avatar>
                <img src="https://cdn.quasar.dev/img/boy-avatar.png">
              </q-avatar>
            </q-item-section>

            <q-item-section>
              <q-item-label>{{ item.user.name }}</q-item-label>
              <q-item-label caption>{{ item.user.email }}</q-item-label>
            </q-item-section>
          </q-item>

          <img :src="'http://localhost:3100/images/' + item.image">

          <q-card-section>
            <q-item-label>{{ item.title }}</q-item-label>
            <q-item-label caption>{{ item.createdDate }}</q-item-label>
            <q-separator class="q-my-md"></q-separator>
            <q-card-section v-html="item.description">
            </q-card-section>
          </q-card-section>

          <q-card-actions>
            <q-list class="rounded-borders" separator style="width: 100%;">
              <q-item-label v-if="item.comments.length > 0" header>Comments</q-item-label>

              <q-item clickable v-ripple v-for="comment in item.comments">
                <q-item-section avatar>
                  <q-avatar>
                    <img :src="`https://cdn.quasar.dev/img/avatar${rand()}.jpg`">
                  </q-avatar>
                </q-item-section>

                <q-item-section>
                  <q-item-label lines="1">{{ comment.username }}</q-item-label>
                  <q-item-label caption lines="2">
                    {{ comment.description }}
                  </q-item-label>
                </q-item-section>

                <q-item-section side top>
                  {{ comment.createdDate }}
                </q-item-section>
              </q-item>

              <div v-if="item.comments.length < 1" class="flex flex-center q-mt-md">
                <q-item-label caption>No Comments</q-item-label>
              </div>

              <div v-if="item.comments.length > 2" class="flex flex-center q-mt-md">
                <q-btn flat class="text-primary">View More</q-btn>
              </div>
            </q-list>

            <q-separator></q-separator>

            <div class="q-mt-md" style="width: 100%;">
              <q-input outlined autogrow v-model="item.comment" label="Write Comment">
                <template v-slot:after>
                  <q-btn round dense flat icon="send" @click="addComment(item)" />
                </template>
              </q-input>
            </div>
          </q-card-actions>
        </q-card>

        <template v-slot:loading>
          <div class="row justify-center q-my-md">
            <q-spinner-dots color="primary" size="40px" />
          </div>
        </template>
      </q-infinite-scroll>
    </div>
  </q-page>
  <q-dialog v-model="newPost">
    <q-card style="width: 700px; max-width: 80vw;">
      <q-card-section>
        <div class="text-h6">New Post</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <form autocorrect="off" autocapitalize="off" autocomplete="off" spellcheck="false">
          <q-input type="textarea" filled label="Blog Title" v-model="blogContent"></q-input>
          <br />
          <q-editor ref="editorRef" @paste="onPaste" v-model="editor" />
        </form>
        <br />
      </q-card-section>
      <q-card-actions align="right" class="bg-white text-teal">
        <q-btn flat label="Save" @click="create()" v-close-popup/>
        <q-btn flat label="Cancel" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { ref, reactive } from 'vue'
import BlogService from 'src/services/blog.service'
import AuthService from 'src/services/auth.service'

const blogService = new BlogService();
const authService = new AuthService();
const user = authService.controlToken().data;
const loading = ref(true);
const blogContent = ref("");
const newPost = ref(false);
const file = ref();
const editor = ref("Blog Content");
const editorRef = ref(null);
const title = ref("");
const posts = ref([]);

blogService.findAll().then(res => {
  loading.value = false;
  posts.value = res.data;
});

function fileSelected(files) {
  file.value = files[0];
}

function create() {
  blogService.create({ title: blogContent.value, description: editor.value }).then(res => {
    if (res.status === 201) {
      posts.value.unshift({
        id: res.data.id,
        title: res.data.title,
        createdDate: `${new Date().getFullYear()}-${new Date().getMonth() + 1}-${new Date().getDate()} ${new Date().toLocaleTimeString()}`,
        image: "drawer5.jpg",
        description: res.data.description,
        user: {
          name: user.name,
          email: user.email
        },
        comment: "",
        comments: []
      });
    }
  });
}

function rand() {
  return Math.floor(Math.random() * 6) + 1;
}

function addComment(post) {
  blogService.addComment({ description: post.comment, postId: post.id }).then(res => {
    if (res.status === 201) {
      post.comments.push({
        id: res.data.id,
        description: res.data.description,
        postId: res.data.postId,
        userId: res.data.userId,
        username: user.name,
        createdDate: `${new Date().getFullYear()}-${new Date().getMonth() + 1}-${new Date().getDate()} ${new Date().toLocaleTimeString()}`
      });
      post.comment = "";
    }
  });
}

function addPost() {
  console.log({ title: blogContent.value, description: editor.value });
  // blogService.create({ title: blogContent.value, description: editor.value });
}

</script>

<style scoped>
.my-card {
  width: 100%;
}
</style>
