/**
 * 単位取得
 */
document.addEventListener('DOMContentLoaded', function() {
  const processItemElms = document.querySelectorAll('[name=process_item_id]');
  processItemElms.forEach(elm => {
    elm.addEventListener('change', async e => {
      const process_item_id = elm.value;
      const unitElm = document.querySelector('[name=num]').nextElementSibling.querySelector('.input-group-text');

      const response = await fetch(
        deliveryParams.fetchRoute, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window._token
          },
          body: JSON.stringify({
            process_item_id
          })
        }
      );
      const data = await response.json();
      unitElm.innerHTML = data[0];
    });
  });
});