
/**
 * 日付の有効性チェック
 * @returns {boolean}
 * @param str
 */
const checkDate = (str) => {
  const date = new Date(str);
  return !isNaN(date.getTime());
}
/**
 * 予定施工期間 終了 Min設定
 */
const adjustScheduleEnd = () => {
  const scheduleDay = document.querySelector('.js-schedule-day').value;
  const startDate = document.querySelector('.js-schedule-start').value;
  const scheduleEndElm = document.querySelector('.js-schedule-end');
  scheduleEndElm.disabled = !(scheduleDay > 0 && startDate);
  if(checkDate(startDate)) {
    let plusDay = Math.floor(Number(scheduleDay));
    if(plusDay <= 1) {
      plusDay = 0;
    } else {
      plusDay--;
    }
    const date = new Date(startDate);
    date.setDate(date.getDate() + plusDay);

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0始まり
    const day = String(date.getDate()).padStart(2, '0');
    scheduleEndElm.min = `${year}-${month}-${day}`;
    scheduleEndElm.value = `${year}-${month}-${day}`;
  }

}
/**
 * 項目追加
 * @param target
 * @returns {Promise<void>}
 */
const addItem = async (target) => {
  const wrapper = target.closest('.js-expense');
  // 各種値
  const process_id = wrapper.dataset.processId;
  const cost_type = wrapper.dataset.costType;
  const title = wrapper.querySelector('[name=title]').value;
  const unit_id = wrapper.querySelector('[name=unit_id]').value;
  // フォーム番号
  const numElm = document.querySelector('.js-expense-num');
  const num = numElm.value;
  // 追加ターゲット
  const bodyElm = wrapper.querySelector('.js-expense-body');
  const response = await fetch(
    edit1Params.fetchRoute, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window._token
      },
      body: JSON.stringify({
        num,
        process_id,
        cost_type,
        title,
        unit_id,
      })
    }
  );
  const data = await response.json();
  if (data.isValid) {
    bodyElm.insertAdjacentHTML('beforeend', data.html);
    // 変更フラグ
    document.querySelector('body').classList.add('is-change');
  }
  numElm.value = data.num;
}
/**
 * 項目削除
 * @param button
 * @returns {Promise<void>}
 */
const deleteItem = async (button) => {
  const row = button.closest('.js-expense-row');
  const expense_custom_item_id = button.dataset.expenseCustomItemId;
  const response = await fetch(
    edit1Params.fetchRoute2, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window._token
      },
      body: JSON.stringify({
        expense_custom_item_id
      })
    }
  );
  const data = await response.json();
  if(data.isValid) {
    row.remove();
    // 変更フラグ
    document.querySelector('body').classList.add('is-change');
  }
}

/**
 計算
 * @param wrapper
 * @returns {Promise<void>}
 */
const calculate = async (wrapper) => {
  // 結果表示要素
  const resultElm = wrapper.querySelector('.js-calculate-result');
  // 式表示要素
  let formulaElm;
  if(edit1Params.isFormula) {
    formulaElm = wrapper.nextElementSibling.querySelector('.js-calculate-formula');
  }
  // 費用タイプ
  const type = wrapper.dataset.type;

  let items = {
    type,
    // 工程ID
    processId: wrapper.dataset.processId,
    // 項目ID
    itemId: wrapper.dataset.itemId,
    // カスタム費用項目フラグ
    isCustom: wrapper.dataset.isCustom,
    // 数量
    num: wrapper.querySelector('.js-calculate-num').value,
    // 単価
    price: wrapper.querySelector('.js-calculate-price').value,
  };
  const response = await fetch(
    edit1Params.fetchRoute3, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window._token
      },
      body: JSON.stringify(items)
    }
  );
  const data = await response.json();
  // 予算金額
  resultElm.innerHTML = data.result.toLocaleString();
  // 式
  if(formulaElm) {
    formulaElm.innerHTML = `結果：${data.formula}`;
  }
}

document.addEventListener('DOMContentLoaded', function() {
  /**
   * 施工予定日数
   * @type {NodeListOf<Element>}
   */
  const scheduleDayElm = document.querySelector('.js-schedule-day');
  const scheduleStartElm = document.querySelector('.js-schedule-start');
  const scheduleEndElm = document.querySelector('.js-schedule-end');

  if(scheduleDayElm && scheduleStartElm && scheduleEndElm) {
    if(scheduleDayElm.value && scheduleStartElm.value) {
      adjustScheduleEnd();
    }
    scheduleDayElm.addEventListener('input', (e) => {
      if(scheduleDayElm.value > 0) {
        scheduleStartElm.disabled = false;
        if(scheduleStartElm.value) {
          scheduleEndElm.disabled = false;
          adjustScheduleEnd();
        }
      } else {
        scheduleStartElm.disabled = true;
        scheduleEndElm.disabled = true;
        scheduleEndElm.value = '';
      }
    });
    scheduleStartElm.addEventListener('input', (e) => {
      adjustScheduleEnd();
    })
  }

  /**
   * 項目追加
   */
  const expenseButtons = document.querySelectorAll('.js-expense-btn');
  if(expenseButtons) {
    expenseButtons.forEach((button) => {
      button.addEventListener('click', async (e) => {
        addItem(e.target);
      });
    })
  }
  /**
   * 項目削除
   */
  const deleteButtons = document.querySelectorAll('.js-expense-delete');
  if(deleteButtons) {
    deleteButtons.forEach((button) => {
      button.addEventListener('click', async (e) => {
        if(!confirm('本当に削除してよろしいですか？')) {
          return false;
        }
        deleteItem(button);
      });
    });
  }
  /**
   * カスタムユニット ドロップダウン
   * @type {NodeListOf<Element>}
   */
  const dropdowns = document.querySelectorAll('.js-dropdown');
  if(dropdowns) {
    dropdowns.forEach(dropdown => {
      // ボタン
      const buttonElm = dropdown.querySelector('button');
      // hidden
      const unitElm = dropdown.querySelector('.js-dropdown-unit');
      // select
      const items = dropdown.querySelectorAll('.js-dropdown-item');
      items.forEach(item => {
        item.addEventListener('click',  e => {
          const num = item.dataset.num;
          buttonElm.innerHTML = item.dataset.label;
          if(num) {
            unitElm.value = num;
          } else {
            unitElm.value = 0;
          }
          // 変更フラグ
          document.querySelector('body').classList.add('is-change');
        });
      })
    });
  }
  /**
   * 計算
   */
  // 初期
  const wrapperElms = document.querySelectorAll('.js-calculate-wrapper');
  if(wrapperElms) {
    wrapperElms.forEach((wrapper) => {
      calculate(wrapper);
    })
    // 次の工程へ
    const nextProcessButton = document.querySelector('.js-next-process');
    const nextProcessElm = document.querySelector('[name=next_process_type]');
    nextProcessButton.addEventListener('click', async (e) => {
      nextProcessElm.value = nextProcessButton.dataset.nextProcess;
    })
  }
  // イベントリスナー
  document.body.addEventListener('change', function (e) {
    if (e.target.matches('input.js-calculate')) {
      calculate(e.target.closest('.js-calculate-wrapper'));
    }
  });
});