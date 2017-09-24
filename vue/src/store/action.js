
export default {
  async update(obj, params) {
    obj.commit('UPDATE', params);
  }
}
