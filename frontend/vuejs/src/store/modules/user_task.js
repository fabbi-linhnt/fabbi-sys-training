import apiCaller from '../../utils/api';

export const state = {
  user_task: null
};

export const mutations = {
  setTasks(state, user_task) {
    state.user_task = user_task;
  },
}

export const actions = {
  getUserTask({}, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/user-task/' + id,
        '',
        response => {
          resolve(response.data.data)
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  storeComment({}, submitComment) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/user-task/' + submitComment['id'],
        submitComment,
        response => {
          resolve(response.data)
        },
        err => {
          reject(err.data)
        }
      )
    })
  }
}
