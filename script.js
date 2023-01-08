const form = document.querySelector('form');
const arrivalDate = form.querySelector('.arrival');
const departureDate = form.querySelector('.departure');
form.addEventListener('submit', (e) => {
  e.preventDefault();
});

// arrivalDate.addEventListener('change', (e) => {
//   console.log(Date(arrivalDate.value));
// });
