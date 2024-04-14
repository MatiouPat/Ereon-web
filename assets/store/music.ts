import { defineStore } from "pinia";
import { UserParameter } from "../entity/userparameter";
import { UserParameterRepository } from "../repository/userparameterRepository";

export const useMusicStore = defineStore('music', {
    state: () => ({
        userParameter: {} as UserParameter
    }),
    getters: {
        getUserParameterId: (state) => {
            return state.userParameter.id;
        },
        getUserVolume: (state) => {
            return state.userParameter.globalVolume;
        }
    },
    actions: {
        async setUserParameter(userParameter: UserParameter): Promise<void>
        {
            this.userParameter = userParameter;
        },
        async setUserVolume(userVolume: number): Promise<void>
        {
            let userParameterRepository =  new UserParameterRepository();
            userParameterRepository.updateGlobalVolume(this.getUserParameterId, userVolume)
            this.userParameter.globalVolume = userVolume;
        }
    }
})