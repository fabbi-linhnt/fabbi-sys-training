import apiCaller from '../../utils/api';
import AuthToken from '../../utils/token';
import AuthRole from '../../utils/role';

export const state = {
    auth: null,
    token: '',
    role: '',
    loading: false,
};

export const getters = {
    token: state => state.token,
    auth: state => state.auth,
    role: state => state.role
};

export const mutations = {
    setToken(state, token) {
        state.token = token;
    },
    removeToken(state) {
        state.token = null;
    },
    setAuth(state, auth) {
        state.auth = auth;
    },
    setRole(state, role) {
        state.role = role;
    }
};

export const actions = {
    getAuth({ commit }) {
        return new Promise((resolve, reject) => {
            apiCaller.postRequest(
                '/api/auth/me',
                '',
                response => {
                    commit('setAuth', response.data);
                    commit('setRole', response.data.role);
                    AuthRole.setRole(response.data.role);
                    resolve(response.data);
                },
                err => {
                    AuthToken.removeToken();
                    AuthRole.removeRole();
                    reject(err.response.data);
                }
            );
        });
    },

    login({ commit }, credential) {
        return new Promise((resolve, reject) => {
            apiCaller.postRequest(
                '/api/auth/login',
                credential,
                response => {
                    commit('setToken', response.data.access_token);
                    AuthToken.setToken(response.data.access_token);
                    resolve(response);
                },
                err => {
                    reject(err.response.data);
                }
            );
        });
    },

    logout({ commit }) {
        return new Promise(resolve => {
            commit('removeToken');
            AuthToken.removeToken();
            resolve();
        });
    },
};

