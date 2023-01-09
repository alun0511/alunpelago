const form = document.querySelector('form');
const arrivalDate = form.querySelector('.arrival');
const departureDate = form.querySelector('.departure');
const roomSelect = form.querySelector('.roomSelect');
const totalcostH3 = document.querySelector('.totalcost');

// form.addEventListener('submit', (e) => {
//   // e.preventDefault();
// });

const calcPrice = () => {
  if ((arrivalDate.value != '') & (departureDate.value != '')) {
    var arrival = new Date(arrivalDate.value);
    var departure = new Date(departureDate.value);
    var roomID = parseInt(roomSelect.value);

    var differenceMilliS = departure.getTime() - arrival.getTime();
    var differenceDays = differenceMilliS / (60 * 60 * 24 * 1000);
    if (differenceDays >= 0) {
      if (roomID === 1) {
        var roomCost = 2;
      }
      if (roomID === 2) {
        var roomCost = 6;
      }
      if (roomID === 3) {
        var roomCost = 8;
      }
      var totalcost = differenceDays * roomCost;
      console.log(differenceDays, totalcost);
      totalcostH3.innerHTML = 'Price total: ' + totalcost + ' credits';
    } else var totalcost = '';
  }
};

roomSelect.addEventListener('change', calcPrice);
arrivalDate.addEventListener('change', calcPrice);
departureDate.addEventListener('change', calcPrice);
