import apiCaller from '../../utils/api';

export const state = {

};

export const getters = {
  listSubject: state => state.test
};



export const mutations = {
};

export const actions = {

  getData({ }, param) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/subjects',
        param,
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
        '/api/subjects/' + id,
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

  addData({ }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        '/api/subjects',
        params,
        response => {
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },

  getSubjectById({ }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/subjects/' + id,
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
  updateData({ }, params) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/subjects/' + params.id,
        params.subjects,
        response => {
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      );
    });
  },


  updateActive({ }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/is_active/update/' + id,
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
  countCourseTaskUsers({ }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/subjects/count/' + id,
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
  listCourse({ }, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/subjects/courses/' + id,
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

  course({ }) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/courses/list',
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

  getAllSubject({ }) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/subject/all',
        '',
        response => {
          resolve(response.data)
        },
        err => {
          reject(err.data)
        }
      )
    });
  }
};

