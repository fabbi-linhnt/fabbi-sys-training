import apiCaller from '../../utils/api';

export const state = {
  userTask: null,
  storeComment: null,
};

export const getters = {
  userTask: state => state.userTask,
  storeComment: state => state.storeComment
};

export const mutations = {
  setUserTask(state, userTask) {
    state.userTask = userTask;
  },
  setStoreComment(state, storeComment) {
    state.storeComment = storeComment;
  }
}

export const actions = {
  GET_USER_TASK({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/reports/' + id,
        '',
        response => {
          commit('setUserTask', response.data);
          resolve(response.data.data);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  STORE_COMMENT({ commit }, submitComment) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/reports/' + submitComment['id'] + '/comment',
        submitComment,
        response => {
          commit('setStoreCommit', response.data);
          resolve(response.data);
        },
        (err) => {
          reject(err.data);
        }
      )
    })
  }
}
