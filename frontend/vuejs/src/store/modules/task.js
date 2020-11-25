import apiCaller from '../../utils/api';

export const state = {
  listTasks: null,
  storeTask: null,
  updateTask: null,
  deleteTask: '',
  taskById: null,
  subjectsOfTask: null,
  usersOfTask: null,
};

export const getters = {
  listTasks: state => state.listTasks,
  storeTask: state => state.storeTask,
  updateTask: state => state.updateTask,
  deleteTask: state => state.deleteTask,
  taskById: state => state.taskById,
  subjectsOfTask: state => state.subjectsOfTask,
  usersOfTask: state => state.usersOfTask
};

export const mutations = {
  setListTasks(state, listTasks) {
    state.listTasks = listTasks;
  },
  setStoreTask(state, storeTask) {
    state.storeTask = storeTask;
  },
  setUpdateTask(state, updateTask) {
    state.updateTask = updateTask;
  },
  setDeleteTask(state, deleteTask) {
    state.deleteTask = deleteTask;
  },
  setTaskById(state, taskById) {
    state.taskById = taskById;
  },
  setSubjectsOfTask(state, subjectsOfTask) {
    state.setSubjectsOfTask = subjectsOfTask;
  },
  setUsersOfTask(state, usersOfTask) {
    state.usersOfTask = usersOfTask
  }
}

export const actions = {
  STORE_TASK({ commit }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        '/api/tasks',
        params,
        response => {
          commit('setStoreTask', response);
          resolve(response);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  GET_TASKS({ commit }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/tasks',
        params,
        response => {
          commit('setListTasks', response.data.data);
          resolve(response.data.data);
        },
        err => {
          reject(err.response);
        }
      )
    });
  },
  DESTROY_TASK({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.deleteRequest(
        '/api/tasks/' + id,
        '',
        response => {
          commit('setDeleteSubject', id);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  GET_TASK_BY_ID({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/tasks/' + id,
        '',
        response => {
          commit('setTaskById', response.data.data);
          resolve(response.data.data);
        },
        err => {
          reject(err.response);
        }
      )
    });
  },
  UPDATE_TASK({ commit }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/tasks/' + params.task.id,
        params,
        response => {
          commit('setUpdateTask', response);
          resolve(response);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  GET_SUBJECTS_OF_TASK({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/tasks/subject-task/' + id,
        '',
        (response) => {
          commit('setSubjectsOfTask', response.data.data);
          resolve(response.data.data);
        },
        err => {
          reject(err.response);
        }
      )
    });
  },
  GET_USERS_OF_TASK({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/task/users/' + id,
        '',
        response => {
          commit('setUsersOfTask', response.data);
          resolve(response.data)
        },
        err => {
          reject(err.data);
        }
      )
    });
  }
}
