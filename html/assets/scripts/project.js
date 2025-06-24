const setUsers = async () => {
  const locationElm = document.querySelector('[name=location_id]');
  const userElms = document.querySelectorAll('.js-user');
  const managerElm = document.querySelector('.js-manager');
  const fieldUser1Elms = document.querySelector('.js-field-user1');
  const fieldUser2Elms = document.querySelector('.js-field-user2');
  const fieldUser3Elms = document.querySelector('.js-field-user3');
  const location_id = locationElm.value;
  if (location_id) {
    const response = await fetch(
      projectParams.fetchRoute, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': window._token
        },
        body: JSON.stringify({
          location_id,
          user_id: projectParams.user_id,
          field_user1_id: projectParams.field_user1_id,
          field_user2_id: projectParams.field_user2_id,
          field_user3_id: projectParams.field_user3_id,
        })
      }
    );
    const data = await response.json();
    console.log(data);
    managerElm.disabled = data.manager.isDisabled === 0;
    if (data.manager.isDisabled === 1) {
      managerElm.innerHTML = data.manager.html
    }
    fieldUser1Elms.disabled = data.fieldUser1.isDisabled === 0;
    if (data.fieldUser1.isDisabled === 1) {
      fieldUser1Elms.innerHTML = data.fieldUser1.html
    }
    fieldUser2Elms.disabled = data.fieldUser2.isDisabled === 0;
    if (data.fieldUser2.isDisabled === 1) {
      fieldUser2Elms.innerHTML = data.fieldUser2.html
    }
    fieldUser3Elms.disabled = data.fieldUser3.isDisabled === 0;
    if (data.fieldUser3.isDisabled === 1) {
      fieldUser3Elms.innerHTML = data.fieldUser3.html
    }
  } else {
    userElms.forEach(elm => {
      elm.value = '';
      elm.disabled = true;
    });
  }
}

document.addEventListener('DOMContentLoaded', function() {
  if(projectParams.formType === 'edit') {
    setUsers();
  }
  const locationElm = document.querySelector('[name=location_id]');
  locationElm.addEventListener('change', async e => {
    setUsers();
  });
});