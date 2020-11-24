import apiCaller from '../../utils/api';

export const state = {
  tasks: null,
};

export const getters = {
  tasks: state => state.tasks
};

export const mutations = {
  setTasks(state, tasks) {
    state.tasks = tasks;
  },
}

export const actions = {
  store({}, params) {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        '/api/tasks',
        params,
        response => {
          resolve(response);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  getTasks({commit}, params) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/tasks',
        params,
        response => {
          commit('setTasks', response.data.data);
          resolve(response.data.data);
        },
        err => {
          reject(err.response);
        }
      )
    });
  },
  destroy({}, id) {
    return new Promise((resolve, reject) => {
      apiCaller.deleteRequest(
        '/api/tasks/' + id,
        '',
        response => {
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  getTaskById({}, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/tasks/' + id,
        '',
        response => {
          resolve(response.data.data);
        },
        err => {
          reject(err.response);
        }
      )
    });
  },
  update({}, params) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/tasks/' + params.task.id,
        params,
        response => {
          resolve(response);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  getSubjectOfTask({}, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/tasks/subject-task/' + id,
        '',
        response => {
          resolve(response.data.data);
        },
        err => {
          reject(err.response);
        }
      )
    });
  },
  getUsersOfTask({ }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/task/users/' + id,
        '',
        response => {
          resolve(response.data)
        },
        err => {
          reject(err.data);
        }
      )
    });
  }
}
