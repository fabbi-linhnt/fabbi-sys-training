import apiCaller from '../../utils/api';

export const state = {
  test: 1
};

export const getters = {
  GET_TEST_STATE: state => state.test
};

export const mutations = {

};

export const actions = {
  getData({ }) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/categories',
        '',
        response => {
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  deleteData({ }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.deleteRequest(
        '/api/category/' + id,
        '',
        response => {
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
};
