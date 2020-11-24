import apiCaller from "../../utils/api";

export const state = {
  course: null
};
export const actions = {
  getData({}, param) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/course',
        param,
        response => {
          resolve(response.data.data);
        },
        err => {
          reject(err.response.data.data);
        }
      )
    });
  },
  destroyCourse({}, id) {
    return new Promise((resolve, reject) => {
        apiCaller.deleteRequest
        (
          '/api/course/' + id,
          '',
          response => {
            resolve(response.data);
          },
          err => {
            reject(err.response.data);
          }
        )
      }
    )
  },
  storeCourse({}, params) {
    return new Promise((resolve, reject) => {
      apiCaller.postRequest(
        '/api/course',
        params,
        response => {
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        }
      )
    })
  },
  updateCourse({}, params) {
    return new Promise((resolve, reject) => {
      apiCaller.putRequest(
        '/api/course/' + params.id,
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
  getIdCourse({}, id) {
    return new Promise((resolve, reject) => {
        apiCaller.getRequest(
          '/api/course/' + id,
          '',
          response => {
            resolve(response.data);
          },
          err => {
            reject(err.response.data);
          }
        )
      }
    )
  },
  getDataCategory({}) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/category',
        '',
        response => {
          resolve(response.data.data);
        },
        err => {
          reject(err.response.data);
        }
      )
    });
  },
  getCategoryByCourse({}, id) {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        '/api/course/category/' + id,
        '',
        response => {
          resolve(response.data);
        },
        err => {
          reject(err.response.data);
        })
    })
  }
}
