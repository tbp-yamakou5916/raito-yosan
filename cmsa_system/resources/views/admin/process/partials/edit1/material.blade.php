@php($class = ['form-control'])
@php($step = 0.01)
<div class="c-grid c-grid--material js-expense-row js-calculate-wrapper"
     data-type="material"
     data-process-id="{{ $item->values['process_id'] }}"
     data-item-id="{{ $item->id }}"
     data-is-custom="{{ (int) $item->values['is_custom'] }}"
     data-num="{{ $num }}"
>
  {{-- 共通hidden --}}
  @include('admin.process.partials.edit1.common')
  <div>
    {{ $item->title }}
  </div>
  {{-- 規格 --}}
  <div class="text-bold">
    <div class="input-group">
      @if($item->standard_is_num)
        {{-- 単位体積重量：セメント（袋）/ セメント（バラ）/ 砂（細骨材） --}}
        @php($f_name = 'standard_name')
        @php($name = 'items[' . $num . '][' . $f_name . ']')
        @php($value = $item->values[$f_name])
        @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-' . $f_name]))
        <div class="input-group">
          {{ html()->number($name, $value, 0, null, 0.01)->class($class2) }}
          <div class="input-group-append">
            <span class="input-group-text">kg</span>
          </div>
        </div>
      @elseif(!$item->values['is_custom'] && $item->standards->isNotEmpty())
        @php($f_name = 'standard_id')
        @php($name = 'items[' . $num . '][' . $f_name . ']')
        @if(!$item->values['is_custom'] && $item->id==14)
          {{-- フリーフォームの場合 --}}
          @php($value = $item->values[$f_name] ?? $item->values['default'])
          @php($array = $item->standards->pluck('title', 'id')->toArray())
        @else
          {{-- その他 --}}
          @php($value = $item->values[$f_name] ?? $item->values['default'])
          @php($array = $item->standards->pluck('title', 'id')->toArray())
        @endif
        {{ html()->select($name, $array, $value)->placeholder(__('common.empty'))->class($class) }}
      @else
        @php($f_name = 'standard_name')
        @php($name = 'items[' . $num . '][' . $f_name . ']')
        @php($value = $item->values[$f_name])
        {{ html()->input('text', $name, $value)->class($class) }}
      @endif
    </div>
  </div>
  {{-- 数量 --}}
  <div>
    @php($f_name = 'num')
    @php($name = 'items[' . $num . '][' . $f_name . ']')
    @php($value = $item->values[$f_name])
    @php($class2 = array_merge($class, ['text-right', 'js-calculate', 'js-calculate-' . $f_name]))
    <div class="input-group">
      {{ html()->number($name, $value, 0, null, $step)->class($class2) }}
      <div class="input-group-append">
        <span class="input-group-text">{!! $item->unitHtml ?? '-' !!}</span>
      </div>
    </div>
  </div>
  {{-- 単価 --}}
  <div>
    @php($f_name = 'price')
    @php($name = 'items[' . $num . '][' . $f_name . ']')
    @php($value = $item->values[$f_name])
    @php($class2 = array_merge($class, ['text-right', 'js-calculate', 'js-calculate-' . $f_name]))
    <div class="input-group">
      {{ html()->number($name, $value, 0)->class($class2) }}
      <div class="input-group-append">
        <span class="input-group-text">円</span>
      </div>
    </div>
  </div>
  {{-- 予算金額 --}}
  <div>
    <div class="input-group c-output">
      @php($class2 = array_merge($class, ['text-right', 'js-calculate-result']))
      <div class="{{ implode(' ', $class2) }}"></div>
      <div class="input-group-append">
        <span class="input-group-text">円</span>
      </div>
    </div>
  </div>
  {{-- ロス率 --}}
  <div>
    @php($f_name = 'rate')
    @php($name = 'items[' . $num . '][' . $f_name . ']')
    @php($value = $item->values[$f_name])
    @php($class2 = array_merge($class, ['text-right', 'js-calculate', 'js-calculate-' . $f_name]))
    <div class="input-group">
      {{ html()->number($name, $value, 0, null, $step)->class($class2) }}
    </div>
  </div>
  {{-- 削除 --}}
  <div class="c-grid-trash">
    @if($item->values['is_custom'])
      <span class="btn btn-default js-expense-delete" data-expense-custom-item-id="{{ $item->id }}"><i class="fa-solid fa-trash-can text-danger"></i></span>
    @endif
  </div>
</div>
