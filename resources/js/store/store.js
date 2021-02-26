import Vue from 'vue/dist/vue';
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    pois: [
        {value: 'ADMIN', key: 1},
        {value: 'SPONSOR', key: 2},
        {value: 'REGISTRANT', key: 3},
    ],
    user:[],

    assets:[
      {type: 'image', name: 'image 1', path: 'https://via.placeholder.com/100'},
      {type: 'image', name: 'image 2', path: 'https://via.placeholder.com/200'},
      {type: 'image', name: 'image 3', path: 'https://via.placeholder.com/300'},
      {type: 'image', name: 'image 4', path: 'https://via.placeholder.com/400'},
      {type: 'image', name: 'image 5', path: 'https://via.placeholder.com/500'},
      {type: 'image', name: 'image 6', path: 'https://via.placeholder.com/600'},
      {type: 'pdf', name: 'pdf 1', path: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'},
      {type: 'pdf', name: 'pdf 2', path: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'},
      {type: 'pdf', name: 'pdf 3', path: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'},
      {type: 'pdf', name: 'pdf 4', path: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'},
      {type: 'pdf', name: 'pdf 5', path: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'},
      {type: 'pdf', name: 'pdf 6', path: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'},
      {type: 'pdf', name: 'pdf 7', path: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'},
    ],  
    
    gallery: [
      {type: 'image', name: 'image 1', path: 'https://via.placeholder.com/400'},
      {type: 'image', name: 'image 2', path: 'https://via.placeholder.com/500'},
      {type: 'image', name: 'image 3', path: 'https://via.placeholder.com/400'},
      {type: 'image', name: 'image 4', path: 'https://via.placeholder.com/500'},
      {type: 'image', name: 'image 5', path: 'https://via.placeholder.com/400'},
      {type: 'image', name: 'image 1', path: 'https://via.placeholder.com/500'},
      {type: 'image', name: 'image 2', path: 'https://via.placeholder.com/400'},
      {type: 'image', name: 'image 3', path: 'https://via.placeholder.com/500'},
      {type: 'image', name: 'image 4', path: 'https://via.placeholder.com/400'},
      {type: 'image', name: 'image 5', path: 'https://via.placeholder.com/500'},
    ],
  },
  mutations: {
    // setter
    // this.$store.commit('change', event.target.value)

    // getter
    // $store.getters.flavor 
    // change(state, flavor) {
    //   state.flavor = flavor
    // }

    changeUser(state, user) {
      state.user = user
    }

  },
  getters: {
    pois: state => state.pois,
    assets: state => state.assets,
    user: state => state.user,
    gallery: state => state.gallery,
  }
})