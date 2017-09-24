import {
  UPDATE
} from "./mutation-types"

export default {
  [UPDATE](state,{
    model,
    key,
    value
  }){
    if ('' === model){
      state[key] = value;
    }else{
      state[model][key] = value;
    }
  },

}
