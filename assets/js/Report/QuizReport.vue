<template>
  <div class="h-screen flex flex-col overflow-hidden">
    <md-toolbar class="md-primary">
      <span class="md-title">My Quiz Report</span>
    </md-toolbar>
    <div class="flex flex-col" v-if="states.quizzesLoaded">
      <div class="flex flex-row p-4">
        <md-field>
          <label>Select Quiz</label>
          <md-select v-model="selectedQuizIndex" @input="loadSelectedQuizReports">
            <md-option
              v-for="(quiz, index) in quizzes"
              :key="`quiz#${quiz.id}`"
              :value="index"
            >
              #{{ quiz.id }} - {{ quiz.name }}
            </md-option>
          </md-select>
        </md-field>
      </div>
      <div class="px-4" v-if="states.reportLoaded">
        <md-table md-card>
          <md-table-row>
            <md-table-head>
              Learner
            </md-table-head>
            <md-table-head>
              Submission Time
            </md-table-head>
            <md-table-head>Score</md-table-head>
            <md-table-head v-for="(question, index) in selectedQuiz.questions" :key="`question-${question.id}`">
              {{ index }}
            </md-table-head>
          </md-table-row>
          <md-table-row v-for="report in reports">
            <md-table-cell>{{ report.learner.firstname }} {{ report.learner.name }}</md-table-cell>
            <md-table-cell>{{ report.submitted_at }}</md-table-cell>
            <md-table-cell>{{ report.score }}</md-table-cell>
            <md-table-cell v-for="(question, index) in selectedQuiz.questions" :key="`answer-${question.id}`">
              {{ getScoreForQuestion(report, question) }}
            </md-table-cell>
          </md-table-row>
        </md-table>
      </div>
    </div>
  </div>
</template>

<script>
  import Api from '../API';

  export default {
    data() {
      return {
        states: {
          quizzesLoaded: false,
          quizzesLoading: false,
          reportLoading: false,
          reportLoaded: false
        },
        quizzes: null,
        reports: null,
        selectedQuizIndex: null
      };
    },
    computed: {
      selectedQuiz: {
        get() {
          if(this.selectedQuizIndex !== null) {
            return this.quizzes[this.selectedQuizIndex];
          }
        },
        set(value) {
          this.quizzes[this.selectedQuizIndex] = value
        }
      }
    },
    methods: {
      getScoreForQuestion(attempt, question) {
        const index = attempt.attempt_question_answers.findIndex(anwser => anwser.question.id === question.id);
        if (attempt.attempt_question_answers[index]) {
          return attempt.attempt_question_answers[index].score;
        }
        return null;
      },
      loadSelectedQuizReports() {
        console.log("loadSelectedQuizReports");
        this.states.reportLoaded = false;
        this.states.reportLoading = true;
        let quizLoadPromise = null;
        if (!this.selectedQuiz.questions) {
          quizLoadPromise = Api.quiz.show(this.selectedQuiz.id)
            .then(fullQuiz => {
              this.selectedQuiz.questions = fullQuiz.questions;
              console.log(this.selectedQuiz);
            });
        } else {
          quizLoadPromise = Promise.resolve();
        }
        quizLoadPromise.then(() => {
          Api.attempt.quizReport(this.selectedQuiz)
            .then(reports => {
              this.states.reportLoading = false;
              this.states.reportLoaded = true;
              this.reports = reports;
            });
        });
        // TODO
      }
    },
    mounted() {
      this.states.quizLoading = true;
      Api.quiz.index()
        .then((quizzes) => {
          this.states.quizzesLoaded = true;
          this.states.quizzesLoading = false;
          this.quizzes = quizzes;
        })
        .catch(() => {
          this.states.quizzesLoading = false;
        });
    },
    name: 'QuizReport',
  }
</script>

<style scoped>

</style>
