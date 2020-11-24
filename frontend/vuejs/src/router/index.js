import Vue from 'vue'
import Router from 'vue-router'
import DashboardLayout from '@/layout/DashboardLayout'
import AuthLayout from '@/layout/AuthLayout'

Vue.use(Router)

export default new Router({
  linkExactActiveClass: 'active',
  mode: 'history',
  routes: [
    {
      path: '/',
      redirect: 'dashboard',
      component: DashboardLayout,
      meta: {
        requiresAuth: true
      },
      children: [{
        path: '/dashboard',
        name: 'dashboard',
        component: () => import( /* webpackChunkName: "demo" */ '@/views/Dashboard.vue')
      },
        {
          path: '/icons',
          name: 'icons',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/Icons.vue')
        },
        {
          path: '/profile',
          name: 'profile',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/UserProfile.vue')
        },
        {
          path: '/maps',
          name: 'maps',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/Maps.vue')
        },
        {
          path: '/tables',
          name: 'tables',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/Tables.vue')
        },
        {
          path: '/course',
          name: 'course',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/Course/Course.vue')
        },
        {
          path: '/tasks',
          name: 'task.list',
          component: () => import('@/views/Tasks/ListTasks.vue')
        },
        {
          path: '/listSubjects',
          name: 'Subjects',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/Subjects/ListSubjects.vue')
        },
        {
          path: '/tasks/update/:id',
          name: 'task.update',
          component: () => import('@/views/Tasks/UpdateCreateTask.vue'),
          props: true,
        },
        {
          path: '/tasks/create',
          name: 'task.create',
          component: () => import('@/views/Tasks/UpdateCreateTask.vue')
        },
        {
          path: '/addSubjects',
          name: 'AddSubjects',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/Subjects/AddCreateSubject.vue')
        },
        {
          path: '/editSubjects/:id',
          name: 'EditSubjects',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/Subjects/AddCreateSubject.vue'),
          props: true,
        },
        {
          path: '/addCourse',
          name: 'AddCourse',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/Course/formCourse.vue')
        },
        {
          path: '/editCourse/:id',
          name: 'EditCourse',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/Course/formCourse.vue'),
          props: true
        },
        {
          path: '/tasks/detail/:id',
          name: 'task.detail',
          component: () => import('@/views/Tasks/DetailTask.vue'),
          props: true
        },
        {
          path: '/users/add',
          name: 'users.add',
          component: () => import('@/views/User/AddUsers.vue'),
          props: true
        },
        {
          path: 'reports/list/:id',
          name: 'report.list',
          component: () => import('@/views/Reports/ListReport.vue'),
          props: true
        },
        {
          path: '/users/detail/:id',
          name: 'users.detail',
          component: () => import('@/views/User/DetailUsers.vue'),
          props: true
        },
        {
          path: '/users/list',
          name: 'users.list',
          component: () => import('@/views/User/ListUsers.vue'),
          props: true
        },
      ]
    },
    {
      path: '/',
      redirect: 'login',
      component: AuthLayout,
      meta: {
        requiresVisitor: true
      },
      children: [{
        path: '/login',
        name: 'login',
        component: () => import( /* webpackChunkName: "demo" */ '@/views/Login.vue')
      },
        {
          path: '/register',
          name: 'register',
          component: () => import( /* webpackChunkName: "demo" */ '@/views/Register.vue')
        }
      ]
    }
  ]
})
