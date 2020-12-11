import apiCaller from '../../utils/api';

export const state = {
  listCategories: null,
  deleteCategory: '',
  updateCategory: null,
  storeCategory: null,
};

export const getters = {
  listCategories: state => state.listCategories,
  deleteCategory: state => state.deleteCategory,
  updateCategory: state => state.updateCategory,
  storeCategory: state => state.storeCategory,
};

export const mutations = {
  setListCategories(state, listCategories) {
    state.listCategories = listCategories;
  },
  setDeleteCategory(state, deleteCategory) {
    state.deleteCategory = deleteCategory;
  },
  setUpdateCategory(state, updateCategory) {
    state.updateCategory = updateCategory;
  },
  setStoreCategory(state, storeCategory) {
    state.storeCategory = storeCategory;
  }
};

export const actions = {
  GET_CATEGORIES({ commit }) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/categories',
        '',
        response => {
          commit('setListCategories', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  DESTROY_CATEGORY({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.deleteRequest(
        '/api/categories/' + id,
        '',
        response => {
          commit('setDeleteCategory', id);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  STORE_CATEGORY({ commit }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        '/api/categories',
        params,
        response => {
          commit('setStoreCategory', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  UPDATE_CATEGORY({ commit }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/categories/' + params.id,
        params.data,
        response => {
          commit('setUpdateCategory', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
};
