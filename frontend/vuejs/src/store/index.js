import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const requireContext = require.context('./modules', false, /.*\.js$/);

const storeModule = requireContext
    .keys()
    .map(file => [file.replace(/(^.\/)|(\.js$)/g, ''), requireContext(file)])
    .reduce((modules, [name, moduleItem]) => {
        if (moduleItem.namespaced === undefined) {
            moduleItem.namespaced = true;
        }
        return { ...modules, [name]: moduleItem };
    }, {});

export default new Vuex.Store({
    modules: storeModule,
    state: {
        loading: false
    },
    mutations: {
        setLoading: (state, value) => {
            state.loading = value;
        }
    }
});
