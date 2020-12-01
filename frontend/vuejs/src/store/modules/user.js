import apiCaller from '../../utils/api';

export const state = {
  listUsers: null,
  storeUser: null,
  deleteUser: ''
};

export const getters = {
  listUsers: state => state.listUsers,
  storeUser:  state => state.storeUser,
  deleteUser: state => state.deleteUser
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
  }
};

export const actions = {
  ADD_USER: ({ commit } ,data) => {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        `api/users`,
        data.params,
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
  }
};
