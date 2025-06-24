/**
 * コメントフォーム追加
 * @returns {Promise<void>}
 */
const addCommentForm = async (button) => {
  const wrapper = button.closest('.js-comment-wrapper');
  const costType = wrapper.dataset.costType;
  const bodyElm = wrapper.querySelector('.js-comment-body');
  const numElm = wrapper.querySelector('.js-comment-num');
  const num = numElm.value;
  const response = await fetch(
    edit2Params.fetchRoute, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window._token
      },
      body: JSON.stringify({
        costType,
        num,
      })
    }
  );
  const data = await response.json();
  numElm.value = data.num;
  bodyElm.insertAdjacentHTML('beforeend', data.html);
}
/**
 * 歩掛かり取得
 * @returns {Promise<void>}
 */
const getRate = async () => {
  const process_term_id = document.querySelector('[name=process_term_id]').value;
  const rateTermElm = document.querySelector('.js-rate-term');
  const rateTotalElm = document.querySelector('.js-rate-total');

  const realDay = document.querySelector('[name=real_day]').value;
  const manHour = document.querySelector('[name=man_hour]').value;

  const response = await fetch(
    edit2Params.fetchRoute2, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window._token
      },
      body: JSON.stringify({
        process_term_id,
        realDay,
        manHour
      })
    }
  );
  const data = await response.json();
  rateTermElm.innerText = data.termRate;
  rateTotalElm.innerText = data.totalRate;
}
/**
 * 材料費各種計算
 * @param target
 * @returns {Promise<void>}
 */
const materialCalculate = async (target) => {
  const wrapper = target.closest('.js-calculate-wrapper');
  const process_item_id = wrapper.dataset.processItemId;
  const process_term_id = document.querySelector('[name=process_term_id]').value;
  const usageSumElm = wrapper.querySelector('.js-calculate-usage-sum');
  const lossRatioElm = wrapper.querySelector('.js-calculate-loss-ratio');
  const response = await fetch(
    edit2Params.fetchRoute3, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window._token
      },
      body: JSON.stringify({
        process_term_id,
        process_item_id,
        usage: target.value,
      })
    }
  );
  const data = await response.json();
  usageSumElm.innerText = data.usageSum;
  lossRatioElm.innerText = data.lossRatio;
}

document.addEventListener('DOMContentLoaded', function() {
  // コメントフォーム追加
  const commentButtons = document.querySelectorAll('.js-comment-button');
  commentButtons.forEach(button => {
    button.addEventListener('click',  async (e) => {
      await addCommentForm(button);
    });
  });

  // 歩掛かり取得
  const manHourElm = document.querySelector('.js-rate');
  manHourElm.addEventListener('input', async (e) => {
    await getRate();
  })
  // 材料費各種計算
  const elms = document.querySelectorAll('.js-calculate');
  elms.forEach(elm => {
    elm.addEventListener('input', async (e) => {
      await materialCalculate(e.target);
    })
  })
  // 分析実行
  const changeModeButton = document.querySelector('.js-change-mode');
  const modeChangeElm = document.querySelector('[name=is_mode_change]');
  changeModeButton.addEventListener('click', async (e) => {
    modeChangeElm.value = 3;
  })
});