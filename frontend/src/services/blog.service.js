import { useQuasar, LocalStorage } from "quasar";
import { api } from "src/boot/axios";
import AuthService from "src/services/auth.service";
import CookieManager from 'js-cookie-manager';

class BlogService {
  q = useQuasar();
  ls = LocalStorage;
  cookieService = new CookieManager();
  http = api;
  httpOptions = {};
  authService = new AuthService();

  constructor() {
    this.httpOptions = {
      headers: {
        Authorization: this.authService.getToken(),
      },
    };
  }

  findAll() {
    this.q.loading.show();
    return this.http.get("/posts", this.httpOptions).then((res) => { this.q.loading.hide(); return res; }).catch(err => {
      if (err.response.status === 401) {
        this.authService.logOut();
      }
    })
  }

  findOne(id) {
    this.q.loading.show();
    return this.http.get(`/posts/${id}`, this.httpOptions).then((res) => { this.q.loading.hide(); return res; });
  }
  
  create(blog) {
    this.q.loading.show();
    return this.http.post(`/posts`, blog, this.httpOptions).then((res) => { this.q.loading.hide(); return res; }).catch(err => {
      if (err.response.status === 401) {
        this.authService.logOut();
      }
    });
  }

  addComment(comment) {
    this.q.loading.show();
    return this.http.post(`/comment`, comment, this.httpOptions).then((res) => { this.q.loading.hide(); return res; }).catch(err => {
      if (err.response.status === 401) {
        this.authService.logOut();
      }
    });
  }

}

export default BlogService