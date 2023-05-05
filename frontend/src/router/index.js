import { route } from 'quasar/wrappers'
import { createRouter, createMemoryHistory, createWebHistory, createWebHashHistory } from 'vue-router'
import routes from './routes'
import AuthService from 'src/services/auth.service';

const authService = new AuthService();

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

export default route(function (/* { store, ssrContext } */) {
  const createHistory = process.env.SERVER
    ? createMemoryHistory
    : (process.env.VUE_ROUTER_MODE === 'history' ? createWebHistory : createWebHashHistory)

  const Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes,

    // Leave this as is and make changes in quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    history: createHistory(process.env.VUE_ROUTER_BASE)
  })

  Router.beforeEach((to, from, next) => {
    if (authService.controlToken()) {
      if (to.name === "login" || to.name === "register") {
        next({ name: "dashboard" });
      }
      else {
        next();
      }
    }
    else {
      if (to.name !== "login") {
        if (to.name === "register") {
          next();
        }
        else {
          next({ name: "login" });
        }
      }
      else {
        next();
      }
    }
  });

  return Router
})
