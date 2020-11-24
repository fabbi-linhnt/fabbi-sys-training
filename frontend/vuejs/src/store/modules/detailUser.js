import apiCaller from '../../utils/api';

export const state = {
  Object: []
};

export const getters = {
  GET_DETAIL_STATE: state => state.Object
};

export const mutations = {
  getInfoData(state,data)
  {
    if(!data.error)
    {
      state.Object = data.data;
    }
    else
    {
      state.Object = error;
    }
  }
};

export const actions = {
  GETDATA_ACTION: ({ commit }, params) => {
    return new Promise((resolve, reject) => {
      apiCaller.getRequest(
        `api/user/getInfo/` + params.params.id,
        null,
        res => {
          commit("getInfoData",res.data);
        },
        err => {
          reject(err);
        }
      );
    });
}
};
