import apiCaller from '../../utils/api';

export const state = {
  listUsers: null,
  storeUser: null,
  deleteUser: '',
  listCourses: null,

};

export const getters = {
  listUsers: state => state.listUsers,
  storeUser:  state => state.storeUser,
  deleteUser: state => state.deleteUser,
  coursesOfUser: state => state.courseOfUser,
  userById: state => state.userById
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
  setListCourses(state, listCourses) {
    state.listCourses = listCourses;
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
}
};
