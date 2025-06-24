/**
 * 単位取得
 */
document.addEventListener('DOMContentLoaded', function() {
  /**
   * 変更チェック
   * 変更があった場合にアラートを表示する
   */
  const inputElms = document.querySelectorAll('input,select,textarea');
  inputElms.forEach(inputElm => {
    if(inputElm.name === 'title') return false;
    inputElm.addEventListener('change', (event) => {
      // 変更フラグ
      document.querySelector('body').classList.add('is-change');
    })
  });
  const anchors = document.querySelectorAll('a:not(.js-check-ignore)');
  anchors.forEach(anchor => {
    anchor.addEventListener('click', (e) => {
      const isChange = document.querySelector('body').classList.contains('is-change');
      if (isChange && !confirm('変更が保存されていませんが、移動しますか？')) {
        e.preventDefault();
      }
    })
  });
});