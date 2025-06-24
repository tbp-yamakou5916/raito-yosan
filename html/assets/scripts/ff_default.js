/**
 * 規格名の取得
 * @param value
 * @param standard_id
 * @returns {Promise<void>}
 */
const getStandard = async (value, standard_id = 0) => {
  const response = await fetch(
    scriptParams.fetchRoute, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window._token
      },
      body: JSON.stringify({
        expense_item_id: value,
        standard_id
      })
    }
  );
  const data = await response.json();
  const standardElm = document.querySelector('[name=standard_id]');
  standardElm.innerHTML = data.html;
  if (data.isDisabled === 1) {
    standardElm.disabled = true;
  } else {
    standardElm.disabled = false;
  }
}

const checkDuplication = async () => {
  const expenseItemId = document.querySelector('[name=expense_item_id]').value;
  const ffCategoryId = document.querySelector('[name=ff_category_id]').value;
  const submitBtn = document.querySelector('.js-submit');
  if(expenseItemId && ffCategoryId) {
    const exists = scriptParams.defaults.some(item =>
      item.ff_category_id === ffCategoryId && item.expense_item_id === expenseItemId
    );
    if(exists) {
      alert('既に存在している組み合わせです');
      submitBtn.classList.add('disabled');
      submitBtn.disabled = true;
    } else {
      submitBtn.classList.remove('disabled');
      submitBtn.disabled = false;
    }
  }
}


document.addEventListener('DOMContentLoaded', function() {
  /**
   * 規格名取得
   * @type {HTMLElement}
   */
  const expenseItemElm = document.querySelector('[name=expense_item_id]');
  // 初期設定
  if(expenseItemElm.value) {
    getStandard(expenseItemElm.value, scriptParams.standardId);
  }
  // イベント
  expenseItemElm.addEventListener('change', async (e) => {
    getStandard(e.target.value);
    if(scriptParams.formType==='create') {
      // 重複チェック
      checkDuplication();
    }
  });
  // 新規作成時のみ
  if(scriptParams.formType==='create') {
    const submitBtn = document.querySelector('.js-submit');
    submitBtn.classList.add('disabled');
    submitBtn.disabled = true;

    const ffCategoryElm = document.querySelector('[name=ff_category_id]');
    ffCategoryElm.addEventListener('change', async (e) => {
      // 重複チェック
      checkDuplication();
    });
  }

});