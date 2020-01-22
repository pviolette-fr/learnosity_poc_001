<template>
    <div class="h-screen w-full flex flex-col">
      <md-toolbar class="md-primary">
        <span class="md-title">Assessment</span>
      </md-toolbar>
      <template v-if="states.quizLoaded">
        <div class="flex-grow p-2 flex flex-col items-stretch px-20">
          <md-card v-for="(question, index) in quiz.questions" :key="`question-${index}`" v-show="index === currentQuestion" class="p-4 w-full h-full">
            <div  :class="`learnosity-response question-${question.id}`" />
          </md-card>
        </div>
        <div class="flex flex-row w-full items-center content-center justify-center" style="height: 128px">
          <md-button @click="onPreviousButtonClick" :disabled="currentQuestion === 0">Previous</md-button>
          <div>{{ `${currentQuestion + 1} / ${quiz.questions.length}` }}</div>
          <md-button @click="onNextButtonClick"  :disabled="currentQuestion === quiz.questions.length - 1">Next</md-button>
          <md-button class="md-fab md-fab-bottom-right" @click="onSubmitButtonClick">Submit</md-button>
        </div>
      </template>
    </div>
</template>

<script>
  import Api from '../API';
    export default {
      props: {
        quizId: {
          type: Number,
          required: true
        }
      },
      data() {
        return {
          states: {
            loadingQuiz: false,
            quizLoaded: false,
            quizLoadingHasError: false,
            startingAttempt: false,
            attemptStarted: false,
            attemptStartHasError: false,
          },
          questionsApp: null,
          currentQuestion: 0,
          quiz: null,
          attempt: null,
          learnosityInit: null
        }
      },
      methods: {
        onPreviousButtonClick() {
          if(this.currentQuestion > 0)
            this.currentQuestion --;
        },
        onNextButtonClick() {
          if (this.currentQuestion < this.quiz.questions.length - 1) {
            this.currentQuestion++;
          }
        },
        onSubmitButtonClick() {
          const answers = this.questionsApp.getResponses();

          const formatted = Object.keys(answers).map(questionId => {
            return {
              quiz_attempt: {
                id: this.attempt.id
              },
              question: {
                id: questionId
              },
              value: answers[questionId].value,
              value_type: answers[questionId].type
            }
          });

          console.log(formatted);
          Api.attempt.submit(this.attempt, formatted);
        },

        loadQuiz(){
          this.states.loadingQuiz = true;
          return Api.quiz.show(this.quizId)
            .then(quiz => {
              this.states.quizLoaded = true;
              this.quiz = quiz;
              this.states.quizLoading = true;
            })
            .catch(err => {
              this.states.quizLoadingHasError = true;
              this.states.quizLoading = true;
              throw err;
            })
        },

        startAttempt() {
          this.states.startingAttempt = true;
          return Api.attempt.start(this.quiz)
            .then(({attempt, learnosityInit}) => {
              this.states.attemptStarted = true;
              this.attempt = attempt;
              this.learnosityInit = learnosityInit
              this.states.startingAttempt = false;
            })
            .catch(err => {
              this.states.attemptStartHasError = true;
              this.states.startingAttempt = false;
              throw err;
            })
        },

        initLearnosity() {
          const callbacks = {
            errorListener: function(e) {
              // Adds a listener to all error codes.
              console.log("Error Code ", e.code);
              console.log("Error Message ", e.msg);
              console.log("Error Detail ", e.detail);
            },

            readyListener: function() {
              console.log("Learnosity Questions API is ready");
            },

            labelBundle: {
              loadingInfo: "Loading page...",
              play: "play",
            },

            saveSuccess: function(response_ids) {
              for(let i = 0; i < response_ids.length; i++) {
                console.log("Responses saved : ", response_ids[i]);
              }
            },

            saveError: function(e) {
              console.log("Save failed - error ", e);
            },

            saveProgress: function(progress) {
              console.log("Save progress - ", progress);
            }
          };

          this.questionsApp = window.LearnosityApp.init(this.learnosityInit, callbacks);
        }
      },
      mounted() {
          this.loadQuiz()
            .then(this.startAttempt)
            .then(this.initLearnosity);
      },
      name: 'QuizAssessment'
    }
</script>

<style scoped>

</style>
