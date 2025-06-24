<div class="row mt-2">
  <div class="col-1 text-right">
    ID：{{ $item->id }}
  </div>
  <div class="col-7">
    式：{{ $item->formula ?? '設定なし' }}
  </div>
  <div class="col-4 js-calculate-formula"></div>
</div>
