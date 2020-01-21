import Axios from './axios'

export const start = (quiz) => {
  const url = `/quiz/${quiz.id}/startAttempt`;

  return Axios.post(url)
    .then(response => {
      const attempt = response.data.attempt;
      const learnosityInit = response.data.learnosity_init;
      return {attempt, learnosityInit}
    })
    .catch(err => {
      console.error(err);
      throw err
    });
};
