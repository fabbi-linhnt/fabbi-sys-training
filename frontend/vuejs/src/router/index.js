import Vue from 'vue'
import Router from 'vue-router'
import DashboardLayout from '@/layout/DashboardLayout'
import AuthLayout from '@/layout/AuthLayout'

Vue.use(Router)

export default new Router({
  linkExactActiveClass: 'active',
  mode: 'history',
  routes: [{
    path: '/',
    redirect: 'dashboard',
    component: DashboardLayout,
    meta: {
      requiresAuth: true
    },
    children: [{
      path: '/dashboard',
      name: 'dashboard',
      component: () =>
        import('@/views/Dashboard.vue')
    },
      {
        path: '/courses/list',
        name: 'courses.list',
        component: () =>
          import('@/views/Course/Course.vue')
      },
      {
        path: '/courses/detail',
        name: 'course.detail',
        component: () =>
          import('@/views/Course/DetailCourse.vue')
      },
      {
        path: '/tasks/list',
        name: 'tasks.list',
        component: () =>
          import('@/views/Tasks/ListTasks.vue')
      },
      {
        path: '/subjects/list',
        name: 'subjects.list',
        component: () =>
          import('@/views/Subjects/ListSubjects.vue')
      },
      {
        path: '/tasks/edit/:id',
        name: 'task.edit',
        component: () =>
          import('@/views/Tasks/UpdateCreateTask.vue'),
        props: true,
      },
      {
        path: '/tasks/create',
        name: 'task.create',
        component: () =>
          import('@/views/Tasks/UpdateCreateTask.vue')
      },
      {
        path: '/subjects/create',
        name: 'subject.create',
        component: () =>
          import('@/views/Subjects/AddUpdateSubject.vue')
      },
      {
        path: '/subjects/edit/:id',
        name: 'subject.edit',
        component: () =>
          import('@/views/Subjects/AddUpdateSubject.vue'),
        props: true,
      },
      {
        path: '/subjects/detail/:id',
        name: 'subject.detail',
        component: () =>
          import('@/views/Subjects/DetailSubject.vue'),
        props: true,
      },
      {
        path: '/courses/detail/:id',
        name: 'course.detail',
        component: () =>
          import('@/views/Course/DetailCourse.vue'),
        props: true,
      },
      {
        path: '/courses/create',
        name: 'course.create',
        component: () =>
          import('@/views/Course/formCourse.vue')
      },
      {
        path: '/courses/edit/:id',
        name: 'course.edit',
        component: () =>
          import('@/views/Course/formCourse.vue'),
        props: true
      },
      {
        path: '/tasks/detail/:id',
        name: 'task.detail',
        component: () =>
          import('@/views/Tasks/DetailTask.vue'),
        props: true
      },
      {
        path: '/users/create',
        name: 'user.create',
        component: () =>
          import('@/views/User/AddUsers.vue'),
        props: true
      },
      {
        path: 'reports/list/:id',
        name: 'reports.list',
        component: () =>
          import('@/views/Reports/ListReport.vue'),
        props: true
      },
      {
        path: '/users/detail/:id',
        name: 'user.detail',
        component: () =>
          import('@/views/User/DetailUsers.vue'),
        props: true
      },
      {
        path: '/users/list',
        name: 'users.list',
        component: () =>
          import('@/views/User/ListUsers.vue'),
        props: true
      },
      {
        path: '/categories/list',
        name: 'categories.list',
        component: () =>
          import('@/views/Category/category.vue'),
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
        component: () =>
          import( /* webpackChunkName: "demo" */ '@/views/Login.vue')
      },
        {
          path: '/register',
          name: 'register',
          component: () =>
            import( /* webpackChunkName: "demo" */ '@/views/Register.vue')
        }
      ]
    }
  ]
})
