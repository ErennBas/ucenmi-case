import { useQuasar, LocalStorage } from "quasar";
import CookieManager from 'js-cookie-manager';
import { api } from "src/boot/axios";

class AuthService {
  q = useQuasar();
  ls = LocalStorage;
  cookieService = new CookieManager();
  http = api;

  controlToken() {
    if (!this.cookieService.has('token')) {
      return false;
    }
    const jwt = this.cookieService.get("token");
    try {
      const jwtData = JSON.parse(atob(jwt.split(".")[1]));
      if (((jwtData.exp * 1000) - Date.now()) > 0) {
        return jwtData;
      }
      else {
        return false;
      }
    } catch (error) {
      return false;
    }
  }

  getToken() {
    return this.cookieService.get("token");
  }

  login(email, password) {
    return this.http.post("/auth/login", { email, password }).then(res => {
      if (res.status === 200) {
        this.cookieService.set("token", res.data.token);
      }
      return res;
    }).catch(err => {
      err.response.data.message.forEach(el => {
        this.q.notify({
          type: "negative",
          timeout: 3000,
          message: el
        });
      });
    });
  }

  register(name, email, password) {
    return this.http.post("/auth/register", { name, email, password }).then(res => {
      if (res.status !== 201) {
        res.data.message.forEach(el => {
          this.q.notify({
            type: "negative",
            timeout: 3000,
            message: el
          });
        });
      }
      return res;
    })
  }

  logOut() {
    this.cookieService.remove("token");
    window.location.reload();
  }
}

export default AuthService