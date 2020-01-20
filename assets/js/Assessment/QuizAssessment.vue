<template>
    <div class="h-screen w-full flex flex-col">
      <md-toolbar class="md-primary">
        <span class="md-title">Assessment</span>
      </md-toolbar>
      <div class="flex-grow bg-red-100 p-2">
        <div v-for="(question, index) in quiz.questions" :key="`question-${index}`" v-show="index === currentQuestion">
          <div  :class="`learnosity-response question-${question.response_id}`" />
        </div>
      </div>
      <div class="flex flex-row w-full items-center content-center justify-center" style="height: 128px">
        <md-button @click="onPreviousButtonClick">Previous</md-button>
        <div>{{ currentQuestion + 1 }}</div>
        <md-button @click="onNextButtonClick">Next</md-button>
        <md-button class="md-fab md-fab-bottom-right" @click="onSubmitButtonClick">Submit</md-button>
      </div>
    </div>
</template>

<script>
    export default {
      data() {
        return {
          questionsApp: null,
          currentQuestion: 0,
          quiz: { // TODO API call
            name: "Mock",
            questions: [
              // TODO mock
            ]
          }
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
          console.log(this.questionsApp.getResponses());
        }
      },
      mounted() {
        const initializationObject = window.cKLearnosityRequest;
      console.log(initializationObject);
        const callbacks = {
          errorListener: function(e) {
            // Adds a listener to all error codes.
            console.log("Error Code ", e.code);
            console.log("Error Message ", e.msg);
            console.log("Error Detail ", e.detail);
          },
          /* example object
          {
              code:   10001,
              msg:    "JSON format error",
              detail: "Activity JSON Poorly formed. Incorrect activity type found: abcd",
          }
          */

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

        this.$set(this.quiz, 'questions', initializationObject.questions);

        this.questionsApp = window.LearnosityApp.init(initializationObject, callbacks);

      },
      name: 'QuizAssessment'
    }
</script>

<style scoped>

</style>
