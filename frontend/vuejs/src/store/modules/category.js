import apiCaller from '../../utils/api';

export const state = {
  listCategories: null,
  deleteCategory: '',
};

export const getters = {
  listCategories: state => state.listCategories,
  deleteCategory: state => state.deleteCategory
};

export const mutations = {
  setListCategories(state, listCategories) {
    state.listCategories = listCategories;
  },
  setDeleteCategory(state, deleteCategory) {
    state.deleteCategory = deleteCategory;
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
        '/api/category/' + id,
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
};
