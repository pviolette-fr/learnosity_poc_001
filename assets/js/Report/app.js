import Vue from 'vue';
import VueMaterial from 'vue-material';
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

import QuizReport from './QuizReport';

Vue.use(VueMaterial);

new Vue({
  el: '#app',
  components: { QuizReport }
});
