import apiCaller from "../../utils/api";

export const state = {
  listCourses: null,
  deleteCourse: '',
  storeCourse: null,
  updateCourse: null,
  courseById: null,
  dataCategories: null,
  categoriesById: null
};

export const getters = {
  listCourses: state => state.listCourses,
  deleteCourse: state => state.deleteCourse,
  storeCourse: state => state.storeCourse,
  updateCourse: state => state.updateCourse,
  courseById: state => state.courseById,
  dataCategories: state => state.dataCategories,
  categoriesById: state => state.categoriesById
};

export const mutations = {
  setListCourses(state, listCourses) {
    state.listCourses = listCourses;
  },
  setDeleteCourse(state, deleteCourse) {
    state.deleteCourse = deleteCourse;
  },
  setStoreCourse(state, storeCourse) {
    state.storeCourse = storeCourse;
  },
  setUpdateCourse(state, updateCourse) {
    state.updateCourse = updateCourse;
  },
  setCourseById(state, courseById) {
    state.courseById = courseById;
  },
  setDataCategories(state, dataCategories) {
    state.dataCategories = dataCategories;
  },
  setCategoriesById(state, categoriesById) {
    state.categoriesById = categoriesById;
  }
}

export const actions = {
  GET_COURSES({ commit }, param) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/courses',
        param,
        response => {
          commit('setListCourses', response.data.data);
          resolve(response.data.data);
        },
        err => {
          reject(err.response.data.data);
        }
      )
    });
  },
  DESTROY_COURSE({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.deleteRequest(
        '/api/courses/' + id,
        '',
        response => {
          commit('setDeleteCourse', id);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      )
    }
    )
  },
  STORE_COURSE({ commit }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        '/api/courses',
        params,
        response => {
          commit('setStoreCourse', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      )
    })
  },
  UPDATE_COURSE({ commit }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/courses/' + params.id,
        params,
        response => {
          commit('setUpdateCourse', response);
          resolve(response);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  GET_ID_COURSE({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/courses/' + id,
        '',
        response => {
          commit('setCourseById', response.data)
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  GET_DATA_CATEGORIES({ commit }) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/categories',
        '',
        response => {
          commit('setDataCategories', response.data.data);
          resolve(response.data.data)
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  GET_CATEGORIES_BY_COURSE({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/courses/categories/' + id,
        '',
        response => {
          commit('setCategoriesById', response.data);
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        })
    });
  },
}
