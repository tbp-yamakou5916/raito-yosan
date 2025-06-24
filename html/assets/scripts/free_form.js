/**
 * 施工情報計算
 * @returns {Promise<void>}
 */
const setResult = async () => {
  // 工程タイプ
  const ffCategoryId = document.querySelector('[name=ff_category_id]').value;
  // ① 面積
  const area = document.querySelector('[name=area]').value;
  // ② 外注基本単価
  const price = document.querySelector('[name=price]').value;
  // ③ 枠内吹付厚さ
  const thickness = document.querySelector('[name=thickness]').value;
  const response = await fetch(
    freeformParams.fetchRoute, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window._token
      },
      body: JSON.stringify({
        ffCategoryId,
        area,
        price,
        thickness
      })
    }
  );
  const data = await response.json()
  // ⑪ 法枠工面積
  //document.querySelector('[name=frame_area]').value = data.frameArea;
  // ⑫ 法枠幅
  document.querySelector('[name=frame_width]').value = data.frameWidth;
  // ⑬ 対象数量
  document.querySelector('[name=quantity]').value = data.quantity;
  // ⑭ 法枠数
  //document.querySelector('[name=frame_num]').value = data.frameNum;
  // ⑮ 1枠の枠内面積
  document.querySelector('[name=one_frame_inner_area]').value = data.oneFrameInnerArea;
  // ⑯ 枠内面積
  document.querySelector('[name=frame_inner_area]').value = data.frameInnerArea;
};

/**
 * ［CD03］法枠規格の設定
 * @param ff_category_id
 */
const setCD03 = (ff_category_id) => {
  document.querySelector('[name=frame_width_type]').value
    = document.querySelector(`[name=ff_category_id] option[value="${ff_category_id}"]`).dataset.type;
};

/**
 * ［CD09］面積の設定
 * cmsa_system/lang/ja/condition.php
 * @param value
 */
const setCD09 = (value) => {
  const area_type =  document.querySelector('[name=area_type]');
  if(100>value) {
    area_type.value = 1;
  } else if(500>value) {
    area_type.value = 2;
  } else if(1000>value) {
    area_type.value = 3;
  } else {
    area_type.value = 4;
  }
};

document.addEventListener('DOMContentLoaded', function() {
  // 施工情報計算
  setResult();
  const elms = document.querySelectorAll('.js-calculate');
  elms.forEach((elm) => {
    elm.addEventListener('change', async (e) => {
      await setResult();
    });
  });

  // ［CD03］法枠規格の設定
  const ffCategoryIdElm = document.querySelector('[name=ff_category_id]');
  if(ffCategoryIdElm.value) {
    setCD03(ffCategoryIdElm.value);
  }
  ffCategoryIdElm.addEventListener('change', async (e) => {
    setCD03(ffCategoryIdElm.value);
  });

  // ［CD09］面積の設定
  // ① 面積
  const areaElm = document.querySelector('[name=area]');
  if(areaElm.value) {
    setCD09(areaElm.value);
  }
  areaElm.addEventListener('input', async (e) => {
    setCD09(areaElm.value);
  });
});