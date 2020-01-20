import axios from './axios';

export const index = () => {
  return axios.get('/quiz/')
    .then(response => {
      return response.data;
    })
    .catch(err => {
      console.error(err);
      throw err;
    });
};

export const show = (id) => {
  const url = `/quiz/${id}`;
  return axios.get(url)
    .then((response) => {
      return response.data; // TODO transformer ?
    })
    .catch(err => {
    console.error(err);
    throw err;
  });
};
