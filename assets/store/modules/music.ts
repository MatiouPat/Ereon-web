import { UserParameter } from "../../entity/userparameter";
import { UserParameterRepository } from "../../repository/userparameterRepository";

const state = {
    userParameter: {} as UserParameter
}

const getters = {
    getUserParameterId: (state) => {
        return state.userParameter.id
    },
    getUserVolume: (state) => {
        return state.userParameter.globalVolume;
    }
}

const actions = {
    setUserParameter: async function({commit}, userParameter: UserParameter): Promise<void>
    {
        commit('setUserParameter', userParameter);
    },
    setUserVolume: async function ({commit, getters}, userVolume: number): Promise<void>
    {
        let userParameterRepository =  new UserParameterRepository();
        userParameterRepository.updateGlobalVolume(getters.getUserParameterId, userVolume)
        commit('setUserVolume', userVolume);
    }
}

const mutations = {
    setUserParameter: function(state: any, userParameter: UserParameter) {
        state.userParameter = userParameter;
    },
    setUserVolume: function(state: any, userVolume: number) {
        state.userParameter.globalVolume = userVolume;
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}