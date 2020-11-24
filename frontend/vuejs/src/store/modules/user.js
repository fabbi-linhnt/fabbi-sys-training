import apiCaller from '../../utils/api';

export const state = {

};

export const getters = {

};

export const mutations = {

};

export const actions = {
  ADD_USER: ({ } ,data) => {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        `api/user`,
        data.params,
        res => {
          resolve(res.data);
        },
        err => {
          reject(err);
        }
      );
    });
  },
  GET_USER: ({ } ,params) => {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        `api/user`,
        params,
        res => {
          resolve(res.data);
        },
        err => {
          reject(err);
        }
      );
    });
  },
  DELETE_USER: ({}, id) => {
    return new Promise((resolve, reject) => {
      apiCaller.deleteRequest(
        '/api/user/' + id,
        null,
        response => {
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  }
};
