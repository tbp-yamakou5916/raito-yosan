const setForm = (value) => {
  const materials = document.querySelectorAll('.js-material');
  const outsourcings = document.querySelectorAll('.js-outsourcing');

  if(value === '1') {
    materials.forEach((elm) => elm.style.display = '');
    outsourcings.forEach((elm) => elm.style.display = 'none');
  } else if(value === '2') {
    materials.forEach((elm) => elm.style.display = 'none');
    outsourcings.forEach((elm) => elm.style.display = '');
  } else {
    materials.forEach((elm) => elm.style.display = 'none');
    outsourcings.forEach((elm) => elm.style.display = 'none');
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const costTypeElm = document.querySelector('.js-cost-type');
  setForm(costTypeElm.value);
  costTypeElm.addEventListener('change', (e) => {
    setForm(e.target.value);
  });
});