import apiCaller from '../../utils/api';

export const state = {
  listUsers: null,
  storeUser: null,
  deleteUser: '',
  coursesOfUser: null,
  userById: null,
  assignUserToCourse: null,
};

export const getters = {
  listUsers: state => state.listUsers,
  storeUser:  state => state.storeUser,
  deleteUser: state => state.deleteUser,
  coursesOfUser: state => state.courseOfUser,
  userById: state => state.userById,
  assignUserToCourse: state => state.assignUserToCourse
};

export const mutations = {
  setListUsers(state, listUsers) {
    state.listUsers = listUsers;
  },
  setStoreUser(state, storeUser) {
    state.storeUser = storeUser;
  },
  setDeleteUser(state, deleteUser) {
    state.deleteUser = deleteUser;
  },
  setCoursesOfUser(state, coursesOfUser) {
    state.coursesOfUser = coursesOfUser;
  },
  setUserById(state, userById) {
    state.userById = userById;
  },
  setAssignUserToCourse(state, assignUserToCourse) {
    state.assignUserToCourse = assignUserToCourse;
  }
};

export const actions = {
  STORE_USER: ({ commit } ,params) => {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        `api/users`,
        params.user,
        res => {
          commit('setStoreUser', res.data);
          resolve(res.data);
        },
        err => {
          reject(err);
        }
      );
    });
  },
  GET_USER: ({ commit } ,params) => {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        `api/users`,
        params,
        res => {
          commit('setListUsers', res.data);
          resolve(res.data);
        },
        err => {
          reject(err);
        }
      );
    });
  },
  GET_COURSES_OF_USER: ({ commit } ,id) => {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        `api/users/` + id + '/courses',
        '',
        res => {
          commit('setCoursesOfUser', res.data);
          resolve(res.data);
        },
        err => {
          reject(err);
        }
      );
    });
  },
  GET_USER_BY_ID: ({ commit } ,id) => {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        `api/users/` + id + '/user-info',
        '',
        res => {
          commit('setUserById', res.data);
          resolve(res.data);
        },
        err => {
          reject(err);
        }
      );
    });
  },
  DELETE_USER: ({ commit }, id) => {
    return new Promise((resolve, reject) => {
      apiCaller.deleteRequest(
        '/api/users/' + id,
        '',
        res => {
          commit('setDeleteUser', res.data);
          resolve(res.data);
        },
        err => {
          reject(err.res.data);
        }
      )
    });
  },
  GET_COURSE: ({ commit }, params) => {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        'api/courses',
        params,
        response =>{
          commit('setListCourses', response.data);
          resolve(response.data);

        },
        err => {
          reject(err.res.data);
        }
      )
    });
  },
  ASSIGN_USER_TO_COURSE({ commit }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        '/api/courses/' + params.id + '/assign-user-to-course',
        params.user,
        response => {
          commit('setAssignUserToCourse', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        })
    });
  },
};
