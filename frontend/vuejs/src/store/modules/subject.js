import apiCaller from '../../utils/api';

export const state = {
  listSubjects: null,
  deleteSubject: '',
  storeSubject: null,
  updateSubject: null,
  subjectById: null,
  coursesByIdSubject: null,
  countTasksCoursesUsers: null,
  updateActive: null,
};

export const getters = {
  listSubjects: state => state.listSubjects,
  deleteSubject: state => state.deleteSubject,
  storeSubject: state => state.storeSubject,
  updateSubject: state => state.updateSubject,
  subjectById: state => state.subjectById,
  coursesByIdSubject: state => state.coursesByIdSubject,
  countTasksCoursesUsers: state => state.countTasksCoursesUsers,
  updateActive: state => state.updateActive
};

export const mutations = {
  setListSubjects(state, listSubjects) {
    state.listSubjects = listSubjects;
  },
  setDeleteSubject(state, deleteSubject) {
    state.deleteSubject = deleteSubject;
  },
  setStoreSubject(state, storeSubject) {
    state.storeSubject = storeSubject;
  },
  setUpdateSubject(state, updateSubject) {
    state.updateSubject = updateSubject;
  },
  setSubjectById(state, subjectById) {
    state.subjectById = subjectById;
  },
  setCoursesByIdSubject(state, coursesByIdSubject) {
    state.coursesByIdSubject = coursesByIdSubject;
  },
  setCountTasksCoursesUsers(state, countTasksCoursesUsers) {
    state.countTasksCoursesUsers = countTasksCoursesUsers;
  },
  setUpdateActive(state, updateActive) {
    state.updateActive = updateActive;
  }
};

export const actions = {

  GET_SUBJECTS({ commit }, param) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/subjects',
        param,
        response => {
          commit('setListSubjects', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  DESTROY_SUBJECT({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.deleteRequest(
        '/api/subjects/' + id,
        '',
        response => {
          commit('setDeleteSubject', id);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  STORE_SUBJECT({ commit }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        '/api/subjects',
        params,
        response => {
          commit('setStoreSubject', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  GET_SUBJECT_BY_ID({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/subjects/' + id,
        '',
        response => {
          commit('setSubjectById', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  UPDATE_SUBJECT({ commit }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/subjects/' + params.subject.id,
        params,
        response => {
          commit('setUpdateSubject', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  UPDATE_ACTIVE({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/is_active/update/' + id,
        '',
        response => {
          commit('setUpdateActive', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  COUNT_COURSES_TASKS_USERS({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/subjects/count/' + id,
        '',
        response => {
          commit('setCountTasksCoursesUsers', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
  GET_COURSES_BY_SUBJECT_ID({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/subjects/courses/' + id,
        '',
        response => {
          commit('setCoursesByIdSubject', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },
};
