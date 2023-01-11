const form = document.querySelector('form');
const arrivalDate = form.querySelector('.arrival');
const departureDate = form.querySelector('.departure');
const roomSelect = form.querySelector('.roomSelect');
const totalcostH3 = document.querySelector('.totalcost');
const hidden = document.querySelectorAll('.hidden');
const roomsWrapper = document.querySelector('.calendar-wrapper');

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

      totalcostH3.innerHTML = 'Price total: ' + totalcost + ' credits';
    } else var totalcost = '';
  }
};

const unHide = () => {
  var roomID = parseInt(roomSelect.value);
  const rooms = roomsWrapper.children;
  const roomsArray = Array.prototype.slice.call(rooms);

  for (let index = 0; index < roomsArray.length; index++) {
    const room = roomsArray[index];

    if (roomID - 1 === index && !room.classList.contains('visible')) {
      console.log(room.classList, 'jag syns inte');
      room.classList.remove('rooms');
      room.classList.add('visible');
    }
    if (roomID - 1 !== index && room.classList.contains('visible')) {
      room.classList.remove('visible');
      room.classList.add('rooms');
    }
  }

  calcPrice();
};

roomSelect.addEventListener('change', unHide);
arrivalDate.addEventListener('change', calcPrice);
departureDate.addEventListener('change', calcPrice);
