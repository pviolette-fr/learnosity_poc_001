import Axios from './axios';

export const create = ({name, config}, quiz) => {
  const url = `/quiz/${quiz.id}/question/`;

  return Axios.post(url, {name, config})
    .then(response => response.data)
    .catch(err => {
      console.error(err);
      throw err;
    });
};

export const update = (question, quiz) => {
  const url = `/quiz/${quiz.id}/question/${question.id}/`;

  return Axios.put(url, question)
    .then(response => response.data)
    .catch(err => {
      console.error(err);
      throw err;
    });
};

export const del = (question, quiz) => {
  const url = `/quiz/${quiz.id}/question/${question.id}/`;

  return Axios.delete(url)
    .then(() => {
      return true;
    })
    .catch(err => {
      console.error(err);
      throw err;
    });
};
