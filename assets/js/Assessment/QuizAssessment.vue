<template>
    <div class="h-screen w-full flex flex-col">
      <md-toolbar class="md-primary">
        <span class="md-title">Assessment</span>
      </md-toolbar>
      <div class="flex-grow bg-red-100 p-2">
        <div v-for="(question, index) in quiz.questions" :key="`question-${index}`" :class="`learnosity-response question-${question.id}`" />
        <span class="learnosity-response question-60005"></span>
      </div>
      <div class="flex flex-row w-full items-center content-center justify-center" style="height: 128px">
        <md-button>Previous</md-button>
        <div>{{ currentQuestion + 1 }}</div>
        <md-button>Next</md-button>
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
      mounted() {
        const initializationObject = window.cKLearnosityRequest;

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

        this.questionsApp = window.LearnosityApp.init(initializationObject, callbacks);

      },
      name: 'QuizAssessment'
    }
</script>

<style scoped>

</style>
