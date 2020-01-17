import Vue from 'vue';
import { MdButton, MdContent, MdTabs, MdIcon, MdCard, MdRipple, MdApp, MdToolbar, MdDrawer, MdList} from 'vue-material/dist/components'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

import QuizAssessment from './QuizAssessment';

Vue.use(MdApp);
Vue.use(MdButton);
Vue.use(MdCard);
Vue.use(MdContent);
Vue.use(MdDrawer);
Vue.use(MdIcon);
Vue.use(MdList);
Vue.use(MdRipple);
Vue.use(MdTabs);
Vue.use(MdToolbar);

new Vue({
  el: '#app',
  template: '<QuizAssessment/>',
  components: { QuizAssessment }
});
