<template>
  <div class="h-screen flex flex-col overflow-hidden">
    <md-toolbar class="md-primary">
      <span class="md-title">My Quizz</span>
    </md-toolbar>
      <div class="flex flex-col justify-between	 w-full flex-grow overflow-hidden">
        <div v-show="questions.length > 0" class="w-full overflow-auto p-2">
          <div class="my-editor" />
        </div>
        <div v-show="questions.length === 0" class="w-full overflow-auto p-2 flex flex-col ">
          <md-content class="max-w-50">
            <p>This quiz is empty</p>
            <md-button class="md-primary md-raised" @click="onAddQuestionClick">Create a question</md-button>
          </md-content>
        </div>
        <div class="flex flex-row pt-4 border-t-2 border-blue-400 bg-white">
          <md-button class="md-fab" @click.prevent="onAddQuestionClick">
            <md-icon>add</md-icon>
          </md-button>
          <div class="w-full flex flex-row content-center overflow-x-scroll" style="height:196px;">
            <div
              v-for="(question, index) in questions"
              :key="`question${index}`"
              @click="selectQuestion(index)"
              class="ml-4"
              style="height: 128px;; min-width: 128px;"
            >
              <md-card
                md-with-hover
                :class="{'border-b-2 border-red-400' : selectedIndex === index}"
              >
                <md-card-header>
                  <p>{{ question.config ? question.config.type: 'New question' }}</p>
                </md-card-header>
                <md-card-content>
                  #{{ question.id }}
                </md-card-content>
                  <md-button class="md-raised md-icon-button" @click.stop="deleteQuestion(index)" style="position: absolute; bottom: -12px; right: -12px">
                    <md-icon>delete</md-icon>
                  </md-button>
              </md-card>
            </div>
          </div>
        </div>
        <md-button class="md-fab md-fab-bottom-right md-primary" md-ripple @click="onSaveButtonClick">
          <md-icon>save</md-icon>
        </md-button>
      </div>
  </div>
</template>
<script>
  import Api from '../API';
  let lastId = 1;
  export default {
    data() {
      return {
        questionEditorApp: null,
        // questions: [
        //   {id: 1, config: null}
        // ],
        questions: [],
        quizId: null,
        selectedIndex: 0
      };
    },
    methods: {
      onSaveButtonClick() {


        const promises = this.questions.map(question => Api.question.update(question, {id: this.quizId}));

        promises.then((questions) => {
          this.$set(this, 'questions', questions);
        })
      },
      onAddQuestionClick() {
        Api.question.create({
          name: `question_` + new Date(),
          config: null
        }, {id: this.quizId}).then(question => {
          this.selectedIndex = this.questions.length;
          this.questions.push(question);
          this.questionEditorApp.reset('response');
        });
      },
      selectQuestion(index) {
        console.log('selectQuestion', {index});
        this.selectedIndex = index;
        const selectedQuestion = this.questions[index];
        if (selectedQuestion.config) {
          this.questionEditorApp.setWidget(selectedQuestion.config);
        } else {
          this.questionEditorApp.reset('response');
        }
      },
      deleteQuestion(index) {
        const question = this.questions[index];
        Api.question.del(question, {id: this.quizId})
          .then(() => {
            this.questions.splice(index, 1);
            if (this.selectedIndex === index && index !== 0) {
              this.selectedIndex = this.selectedIndex - 1;
            }
            if (this.selectedIndex >= this.questions.length) {
              this.selectedIndex = this.questions.length - 1;
            }
            const newSelectedQuestion = this.questions[this.selectedIndex];
            console.log({newSelectedQuestion});
            if (newSelectedQuestion.config) {
              this.questionEditorApp.setWidget(newSelectedQuestion.config);
            } else {
              this.questionEditorApp.reset('response');
            }
          });
      },
      updateQuestion(index) {
        const question = this.questions[index];
        Api.question.update(question, {id: this.quizId})
        .then((question) => {
          this.questions[index] = question;
        });
      }
    },
    name: 'QuizAuthoring',
    mounted() {
      Api.quiz.show(27).then((data) => {
        this.$set(this, 'questions', data.questions);
        this.quizId = data.id;
        const initOptions = {
          'assetRequest': function (mediaRequested, returnType, callback, attributes) {
            // Do something.
          },
          'configuration': {
            'consumer_key': 'ts34Rdc45SWE34f'
          },
          'label_bundle': {
            // question attributes
            'stimulus': 'Compose question',
            'options': 'Options',
            'validation.alt_responses.score': 'Points',
          },
          'question_type_templates': {
            'mcq': [{
              'name': 'MCQ - Custom Style',
              'reference': 'customMCQ',
              'group_reference': 'mcq',
              'description': 'Multiple Choice question with block style and predefined options.',
              'defaults': {
                'options': [{
                  'label': 'Dublin',
                  'value': '1'
                }, {
                  'label': 'Bristol',
                  'value': '2'
                }, {
                  'label': 'Liverpool',
                  'value': '3'
                }, {
                  'label': 'London',
                  'value': '4'
                }],
                // A newly added option will have the default label "New Label"
                'options[]': 'New Label',
                'ui_style': {
                  'type': 'block',
                  'columns': 1,
                  'choice_label': 'upper-alpha'
                }
              }
            }]
          },
          'ui': {
            'layout': {
              'global_template': 'edit',
              // 'responsive_edit_mode': {
              //   'breakpoint': 800 // If the container width becomes less than 800px then switch to edit layout
              // }
            }
          },
          'widget_type': 'response'
        };
        const hook = '.my-editor';
        const vm = this;
        const callbacks = {
          'readyListener': function () {
            if(vm.questions.length > 0) {
              vm.selectQuestion(0);
            }
            // Question Editor API sucessfully loaded according to pased init options
            console.log(' Question Editor API sucessfully loaded according to pased init options');
            // we can now reliably start calling public methods and listen to events
            vm.questionEditorApp.on('widget:changed', function () {
              // Save config inside VueJS
              console.log('widget:changed');
              vm.$set(vm.questions[vm.selectedIndex], 'config', vm.questionEditorApp.getWidget());
            });
            vm.questionEditorApp.on('revisionHistoryState:change', function () {
              console.log('revisionHistoryState:change');
              vm.$set(vm.questions[vm.selectedIndex], 'config', vm.questionEditorApp.getWidget());

            });
          },
          'errorListener': function (e) {
            //callback to occur on error
            console.log('Error code ', e.code);
            console.log('Error message ', e.message);
            console.log('Error name ', e.name);
            console.log('Error name ', e.title);
          }
        };
        this.questionEditorApp = window.LearnosityQuestionEditor.init(initOptions, hook, callbacks);
      });
    }
  }
</script>

<style scoped>

</style>
