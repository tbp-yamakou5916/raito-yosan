document.addEventListener('DOMContentLoaded', function() {
  /**
   * モード変更
   * @type {HTMLElement}
   */
  const modeElms = document.querySelectorAll('.js-mode');
  modeElms.forEach((elm) => {
    elm.addEventListener('click', async function (e) {
      e.preventDefault();
      /*
      if (!confirm('モード変更時に画面をリロードします。保存されていないデータが失われますがよろしいですか？')) {
        return false;
      }
       */
      const modeNum = elm.dataset.modeNum;
      const response = await fetch(
        baseParams.fetchRoute, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window._token
          },
          body: JSON.stringify({
            modeNum
          })
        }
      );

      const newModeNum = await response.json();
      if(newModeNum) {
        // 再読み込み
        location.reload();
      }
    });
  });
  /**
   * date min max 設定
   * ※西暦が5桁入力できてしまうため
   * @type {HTMLElement}
   */
  const dateElms = document.querySelectorAll('input[type=date]');
  dateElms.forEach((elm) => {
    elm.min = '1970-01-01';
    const date = new Date();
    date.setFullYear(date.getFullYear() + 10);

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0始まり
    const day = String(date.getDate()).padStart(2, '0');
    elm.max = `${year}-${month}-${day}`;
  });
});